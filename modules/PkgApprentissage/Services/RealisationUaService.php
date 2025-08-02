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
    public function dataCalcul($realisationUa)
    {
        // En Cas d'édit
        if(isset($realisationUa->id)){
          
        }
      
        return $realisationUa;
    }

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
        $realisationUa->loadMissing([
            'realisationChapitres',
            'realisationUaPrototypes',
            'realisationUaProjets'
        ]);

        // Étape 1 : Agréger les trois types de réalisations
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

            $baremePart = $items->sum(fn($e) => $e->bareme ?? 0);
            $notePart = $items->sum(fn($e) => $e->note ?? 0);
            $progressionPart = $items->filter(fn($e) => $this->isItemTermine($e))->count();
            $totalPart = $items->count();

            if ($totalPart > 0) {
                $progression += ($progressionPart / $totalPart) * $poids;
            }

            $totalNote += $notePart * $poids / 100;
            $totalBareme += $baremePart * $poids / 100;
        }

        $realisationUa->progression_cache = round($progression, 1);
        $realisationUa->note_cache = round($totalNote, 2);
        $realisationUa->bareme_cache = round($totalBareme, 2);
        $realisationUa->save();


        // 🔁 Mise à jour automatique de l’état de la RealisationUa depuis les chapitres
        $nouvelEtatCode = $this->calculerEtat($realisationUa);
        if ($nouvelEtatCode) {
            $nouvelEtat = EtatRealisationUa::where('code', $nouvelEtatCode)->first();
            if ($nouvelEtat && $realisationUa->etat_realisation_ua_id !== $nouvelEtat->id) {
                $realisationUa->etat_realisation_ua_id = $nouvelEtat->id;
                $realisationUa->save();
            }
        }

        // calculeProgrsssion et Note de RealisationMicroCompetence
        $realisationMicroCompetenceService = new RealisationMicroCompetenceService();
        $realisationMicroCompetenceService->calculerProgressionEtNote($realisationUa->realisationMicroCompetence);


       

    }


    private function isItemTermine($item): bool
    {
        // Cas chapitre : on teste le code de l’état du chapitre
        if (isset($item->etatRealisationChapitre)) {
            return optional($item->etatRealisationChapitre)->code === 'DONE';
        }

        // Cas prototype ou projet : il faut charger l’état via la relation realisationTache
        if (method_exists($item, 'realisationTache') && $item->relationLoaded('realisationTache')) {
            return optional($item->realisationTache?->etatRealisationTache)->code === 'DONE';
        }

        // Si la relation n’est pas chargée, on tente dynamiquement (fallback)
        if (isset($item->realisation_tache_id)) {
            $etat = optional($item->realisationTache?->etatRealisationTache)->code;
            return $etat === 'DONE';
        }

        return false;
    }


    public function calculerEtat(RealisationUa $ua): ?string
    {
        $ua->loadMissing([
            'realisationChapitres.etatRealisationChapitre',
            'realisationUaPrototypes.realisationTache.etatRealisationTache',
            'realisationUaProjets.realisationTache.etatRealisationTache',
        ]);

        $chapitres = $ua->realisationChapitres;
        $prototypes = $ua->realisationUaPrototypes;
        $projets = $ua->realisationUaProjets;

        // 🎯 Cas 1 : tous les chapitres sont TODO
        if ($chapitres->count() > 0 &&
            $chapitres->every(fn($c) => optional($c->etatRealisationChapitre)->code === 'TODO')) {
            return 'TODO';
        }

        // 🎯 Cas 2 : tout est DONE
        $allChapitresDone = $chapitres->every(fn($c) => optional($c->etatRealisationChapitre)->code === 'DONE');
        $allPrototypesDone = $prototypes->every(fn($p) =>
            optional($p->realisationTache?->etatRealisationTache)->code === 'DONE'
        );
        $allProjetsDone = $projets->every(fn($p) =>
            optional($p->realisationTache?->etatRealisationTache)->code === 'DONE'
        );

        if ($allChapitresDone && $allPrototypesDone && $allProjetsDone) {
            return 'DONE';
        }

        // 🎯 Cas 3 : au moins un projet est en IN_PROGRESS_PROJET
        if ($projets->contains(fn($p) =>
            optional($p->realisationTache?->etatRealisationTache)->code === 'IN_PROGRESS_PROJET'
        )) {
            return 'IN_PROGRESS_PROJET';
        }

        // 🎯 Cas 4 : au moins un prototype est en IN_PROGRESS_PROTOTYPE
        if ($prototypes->contains(fn($p) =>
            optional($p->realisationTache?->etatRealisationTache)->code === 'IN_PROGRESS_PROTOTYPE'
        )) {
            return 'IN_PROGRESS_PROTOTYPE';
        }

        // 🎯 Cas 5 : au moins un chapitre est en IN_PROGRESS_CHAPITRE (ou équivalent imitation)
        if ($chapitres->contains(fn($c) =>
            in_array(optional($c->etatRealisationChapitre)->code, ['IN_PROGRESS', 'IN_PROGRESS_CHAPITRE'])
        )) {
            return 'IN_PROGRESS_CHAPITRE';
        }

        // 🎯 Fallback : TODO par défaut
        return 'TODO';
    }



}
