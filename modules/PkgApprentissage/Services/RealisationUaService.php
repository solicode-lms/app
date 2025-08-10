<?php


namespace Modules\PkgApprentissage\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Modules\PkgApprentissage\Models\EtatRealisationUa;
use Modules\PkgApprentissage\Models\RealisationUa;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaService;
use Modules\PkgCompetences\Models\UniteApprentissage;

/**
 * Classe RealisationUaService pour gérer la persistance de l'entité RealisationUa.
 */
class RealisationUaService extends BaseRealisationUaService
{
  

    public function afterCreateRules(RealisationUa $realisationUa): void
    {
        // Ajouter automatiquement les réalisations des chapitres liés à l'unité d'apprentissage
        $realisationChapitreService = new RealisationChapitreService();
        $etat_realisation_chapitre_id = EtatRealisationChapitre::where('code', "TODO")->value('id');
        $chapitres = $realisationUa->uniteApprentissage->chapitres;

        foreach ($chapitres as $chapitre) {
            // Vérifier si la réalisation du chapitre existe déjà
            $exists = $realisationChapitreService->model
                ->where('realisation_ua_id', $realisationUa->id)
                ->where('chapitre_id', $chapitre->id)
                ->exists();

            if (! $exists) {
                $realisationChapitreService->create([
                    'realisation_ua_id' => $realisationUa->id,
                    'chapitre_id' => $chapitre->id,
                    'etat_realisation_chapitre_id' => $etat_realisation_chapitre_id,
                ]);
            }
        }
    }



    public function afterUpdateRules($realisationUa): void
    {
        // ✅ Initialiser la date_debut si elle est encore vide
        if (empty($realisationUa->date_debut)) {
            $realisationUa->date_debut = now();
            $realisationUa->save();
        }
        // Recalcul des agrégats
        $this->calculerProgressionEtNote($realisationUa);
    }

    /**
     * Récupère la réalisation UA d'un apprenant pour une unité d'apprentissage donnée.
     * Si elle n'existe pas, elle est générée automatiquement via la réalisation de micro-compétence.
     *
     * @param  int $apprenantId
     * @param  int $uniteApprentissageId
     * @return RealisationUa
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getOrCreateApprenant(int $apprenantId, int $uniteApprentissageId): RealisationUa
    {
        // Vérifier si la réalisation UA existe déjà
        $realisationUa = $this->model
            ->where('unite_apprentissage_id', $uniteApprentissageId)
            ->whereHas('realisationMicroCompetence', fn($query) =>
                $query->where('apprenant_id', $apprenantId)
            )
            ->first();

        if ($realisationUa) {
            return $realisationUa;
        }

        // Identifier la micro-compétence liée à l'unité d'apprentissage
        $microCompetenceId = UniteApprentissage::findOrFail($uniteApprentissageId)
            ->micro_competence_id;

        // Forcer la création via la réalisation de micro-compétence
        (new RealisationMicroCompetenceService())
            ->getOrCreateByApprenant($apprenantId, $microCompetenceId);

        // Rechercher à nouveau la réalisation UA (elle est créée par afterCreateRules)
        return $this->model
            ->where('unite_apprentissage_id', $uniteApprentissageId)
            ->whereHas('realisationMicroCompetence', fn($query) =>
                $query->where('apprenant_id', $apprenantId)
            )
            ->firstOrFail();
    }


public function calculerProgressionEtNote(RealisationUa $realisationUa): void
{
    $realisationUa->load([
        'realisationChapitres',
        'realisationUaPrototypes',
        'realisationUaProjets'
    ]);

    // 🧮 Définition des parties et des poids associés
    $parts = [
        'chapitres' => [
            'items' => $realisationUa->realisationChapitres,
            'poids' => 20,
        ],
        'prototypes' => [
            'items' => $realisationUa->realisationUaPrototypes,
            'poids' => 30,
        ],
        'projets' => [
            'items' => $realisationUa->realisationUaProjets,
            'poids' => 50,
        ],
    ];

    $totalNote = 0;
    $totalBareme = 0;
    $progression = 0;

    foreach ($parts as $part) {
        $items = $part['items'];
        $poids = $part['poids'];

        $count = $items->count();
        if ($count === 0) {
            continue;
        }

        $bareme = $items->sum(function ($e) {
            return $e->note !== null ? ($e->bareme ?? 0) : 0;
        });
        $note = $items->sum(fn($e) => $e->note ?? 0);
        $termines = $items->filter(fn($e) => $this->isItemTermine($e))->count();

        $progression += ($termines / $count) * $poids;
        $totalNote += $note ;
        $totalBareme += $bareme;
    }

    $realisationUa->progression_cache = round($progression, 1);
    $realisationUa->note_cache = round($totalNote, 2);
    $realisationUa->bareme_cache = round($totalBareme, 2);

    // 🔁 Mise à jour de l’état
    $nouvelEtatCode = $this->calculerEtat($realisationUa);
    if ($nouvelEtatCode) {
        $nouvelEtat = EtatRealisationUa::where('code', $nouvelEtatCode)->first();
        if ($nouvelEtat && $realisationUa->etat_realisation_ua_id !== $nouvelEtat->id) {
            $realisationUa->etat_realisation_ua_id = $nouvelEtat->id;
        }
    }

    $realisationUa->save();

    // 🔁 Recalcul micro-compétence par agrégation des UAs
    (new RealisationMicroCompetenceService())
        ->calculerProgressionEtNote($realisationUa->realisationMicroCompetence);
}


    private function isItemTermine($item): bool
    {
        // Cas chapitre : on teste le code de l’état du chapitre
        if (isset($item->etatRealisationChapitre)) {
            return optional($item->etatRealisationChapitre)->code === 'DONE';
        }

        if (isset($item->realisation_tache_id)) {
            $etat = $item->realisationTache?->etatRealisationTache?->workflowTache->code;
            return $etat === 'APPROVED';
        }

        return false;
    }


    /**
     * Calcule l’état global d’une réalisation d’unité d’apprentissage (UA),
     * en fonction de l’avancement des chapitres, prototypes et projets.
     *
     * Règles d'évaluation :
     * - Si tous les chapitres sont en TODO → état = TODO
     * - Si tous les chapitres, prototypes et projets sont en DONE → état = DONE
     * - Si chapitres et prototypes sont DONE → état = IN_PROGRESS_PROJET
     * - Si seuls les chapitres sont DONE → état = IN_PROGRESS_PROTOTYPE
     * - Si au moins un chapitre est DONE → état = IN_PROGRESS_CHAPITRE
     * - Sinon → état = TODO
     *
     * @param RealisationUa $ua  L’unité d’apprentissage à évaluer
     * @return string|null       Le code de l’état calculé
     */
    public function calculerEtat(RealisationUa $ua): ?string
    {
        $ua->load([
            'realisationChapitres.etatRealisationChapitre',
            'realisationUaPrototypes.realisationTache.etatRealisationTache',
            'realisationUaProjets.realisationTache.etatRealisationTache',
        ]);

        $chapitres = $ua->realisationChapitres;
        $prototypes = $ua->realisationUaPrototypes;
        $projets = $ua->realisationUaProjets;

        // 🎯 Cas 1 : Tous les chapitres sont TODO
        if ($chapitres->count() > 0 &&
            $chapitres->every(fn($c) => optional($c->etatRealisationChapitre)->code === 'TODO')) {
            return 'TODO';
        }

        // 🎯 Cas 2 : Tous chapitres, prototypes, projets = DONE
        $allChapitresDone = $chapitres->every(fn($c) => optional($c->etatRealisationChapitre)->code === 'DONE');
        $allPrototypesDone = $prototypes->every(fn($p) =>
            $p->realisationTache?->etatRealisationTache->workflowTache->code === 'APPROVED'
        );
        $allProjetsDone = $projets->every(fn($p) =>
            $p->realisationTache?->etatRealisationTache->workflowTache->code === 'APPROVED'
        );

        if ($allChapitresDone && $allPrototypesDone && $allProjetsDone) {
            return 'DONE';
        }

        if ($allChapitresDone && $allPrototypesDone) {
            return 'IN_PROGRESS_PROJET';
        }

        if ($allChapitresDone) {
            return 'IN_PROGRESS_PROTOTYPE';
        }

        // ✅ Cas ajouté : au moins un chapitre terminé
        if ($chapitres->contains(fn($c) => optional($c->etatRealisationChapitre)->code === 'DONE')) {
            return 'IN_PROGRESS_CHAPITRE';
        }

        return 'TODO';
    }





}
