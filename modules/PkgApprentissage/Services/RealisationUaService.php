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

            if (!$exists) {
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
        $this->calculerProgression($realisationUa);
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
        // 1️⃣ Vérifier si la réalisation UA existe déjà pour cet apprenant
        $realisationUa = $this->model
            ->where('unite_apprentissage_id', $uniteApprentissageId)
            ->whereHas(
                'realisationMicroCompetence',
                fn($query) =>
                $query->where('apprenant_id', $apprenantId)
            )
            ->first();

        if ($realisationUa) {
            return $realisationUa;
        }

        // 2️⃣ Récupérer l’UA et son module
        $ua = UniteApprentissage::with('microCompetence.competence.module')
            ->findOrFail($uniteApprentissageId);

        $moduleId = $ua->microCompetence?->competence?->module_id;

        if (!$moduleId) {
            throw new \RuntimeException("Impossible de déterminer le module lié à l’unité d’apprentissage #$uniteApprentissageId");
        }

        // 3️⃣ Créer RealisationModule si inexistant
        $realisationModuleService = new RealisationModuleService();
        $realisationModuleService->getOrCreateByApprenant($apprenantId, $moduleId);

        // ✅ 4️⃣ S’assurer qu’il existe une Réalisation de la micro-compétence
        // (sinon on ne peut pas lier la RealisationUa)
        $realisationMicroCompetenceService = new RealisationMicroCompetenceService();
        $realisationMicroCompetence = $realisationMicroCompetenceService->getOrCreateApprenant(
            $apprenantId,
            $ua->micro_competence_id
        );

        // ✅ 5️⃣ Créer la Réalisation UA si elle n’existe toujours pas
        $this->model->firstOrCreate(
            [
                'unite_apprentissage_id' => $uniteApprentissageId,
                'realisation_micro_competence_id' => $realisationMicroCompetence->id,
            ],
            [
                // Tu peux initialiser d’autres champs ici si tu veux
                // 'date_debut' => now(),
            ]
        );

        // 6️⃣ Rechercher à nouveau la réalisation UA (qu’elle vienne d’afterCreateRules ou du firstOrCreate)
        return $this->model
            ->where('unite_apprentissage_id', $uniteApprentissageId)
            ->whereHas(
                'realisationMicroCompetence',
                fn($query) =>
                $query->where('apprenant_id', $apprenantId)
            )
            ->firstOrFail();
    }

    public function calculerProgression(RealisationUa $realisationUa): void
    {
        $realisationUa->load([
            'realisationChapitres.etatRealisationChapitre',
            'realisationUaPrototypes.realisationTache.etatRealisationTache',
            'realisationUaProjets.realisationTache.etatRealisationTache'
        ]);

        $poidsPrototypes = $realisationUa->realisationChapitres->isEmpty() ? 50 : 30;

        // 🧮 Définition des parties et des poids associés
        $parts = [
            'chapitres' => [
                'items' => $realisationUa->realisationChapitres,
                'poids' => 20,
            ],
            'prototypes' => [
                'items' => $realisationUa->realisationUaPrototypes,
                'poids' => $poidsPrototypes,
            ],
            'projets' => [
                'items' => $realisationUa->realisationUaProjets,
                'poids' => 50,
            ],
        ];

        $totalNote = 0;
        $totalBareme = 0;
        $progressionReelle = 0;
        $progressionIdeale = 0;
        $pourcentageNonValide = 0;

        foreach ($parts as $part) {
            $items = $part['items'];
            $poids = $part['poids'];

            if ($items->isEmpty()) {
                continue;
            }

            // ✅ Séparer les tâches "activées" (≠ TODO)
            $actives = $items->filter(fn($e) => $this->isActive($e));

            $countAll = $items->count();   // total (utile pour progression réelle)
            $countActif = $actives->count(); // seulement ≠ TODO

            // 🟢 Progression réelle (toutes les tâches comptent)
            $termines = $items->filter(fn($e) => $this->isActiveProgress($e))->count();
            $progressionReelle += ($termines / $countAll) * $poids;

            // 🎯 Progression idéale (seules les tâches activées comptent)
            if ($countActif > 0) {
                $progressionIdeale += ($countActif / $countAll) * $poids;
            }

            // ⛔ Pourcentage Non Valide
            $nonValides = $items->filter(fn($e) => $this->isNonValide($e))->count();
            $pourcentageNonValide += ($nonValides / $countAll) * $poids;

            // Notes
            $bareme = $items->sum(fn($e) => $e->note !== null ? ($e->bareme ?? 0) : 0);
            $note = $items->sum(fn($e) => $e->note ?? 0);
            $totalNote += $note;
            $totalBareme += $bareme;
        }

        $realisationUa->progression_cache = (float) number_format($progressionReelle, 2, '.', '');
        $realisationUa->progression_ideal_cache = (float) number_format($progressionIdeale, 2, '.', '');
        $realisationUa->pourcentage_non_valide_cache = (float) number_format($pourcentageNonValide, 2, '.', '');
        $realisationUa->note_cache = (float) number_format($totalNote, 2, '.', '');
        $realisationUa->bareme_cache = (float) number_format($totalBareme, 2, '.', '');

        // ✅ Taux de rythme
        $realisationUa->taux_rythme_cache = $realisationUa->progression_ideal_cache > 0
            ? $this->formatPourcentage(($progressionReelle / $realisationUa->progression_ideal_cache) * 100)
            : null;

        // 🔁 Mise à jour de l’état
        $nouvelEtatCode = $this->calculerEtat($realisationUa);
        if ($nouvelEtatCode) {
            $nouvelEtat = EtatRealisationUa::where('code', $nouvelEtatCode)->first();
            if ($nouvelEtat && $realisationUa->etat_realisation_ua_id !== $nouvelEtat->id) {
                $realisationUa->etat_realisation_ua_id = $nouvelEtat->id;
            }
        }

        $realisationUa->dernier_update = now();
        $realisationUa->saveQuietly();

        // 🔁 Recalcul micro-compétence par agrégation des UAs
        (new RealisationMicroCompetenceService())
            ->calculerProgression($realisationUa->realisationMicroCompetence);
    }

    private function formatPourcentage(float $valeur): float
    {
        // Arrondi standard à 2 décimales
        $valeur = (float) number_format($valeur, 2, '.', '');

        // ✅ Forcer à 100 si >= 99.95
        if ($valeur >= 99.95) {
            return 100.00;
        }

        // ✅ Ne jamais dépasser 100
        return min(100.00, $valeur);
    }


    private function isNonValide($item): bool
    {
        // ✅ États considérés comme "Invalides" ou "À corriger"
        $etatsInvalides = ['NOT_VALIDATED'];

        if (isset($item->realisation_tache_id)) {
            $etat = $item->realisationTache?->etatRealisationTache?->workflowTache?->code;
            return $etat !== null && in_array($etat, $etatsInvalides, true);
        }

        return false;
    }

    private function isActiveProgress($item): bool
    {
        // ✅ États qui NE sont PAS en cours
        $etatsTacheInProgress = ['READY_FOR_LIVE_CODING', 'IN_LIVE_CODING', 'TO_APPROVE', 'APPROVED'];

        if (isset($item->realisation_tache_id)) {
            $etat = $item->realisationTache?->etatRealisationTache?->workflowTache?->code;

            // 🚀 En cours si état défini et pas dans la liste des "non en cours"
            return $etat !== null && in_array($etat, $etatsTacheInProgress, true);
        }

        return false;
    }


    /**
     * Détermine si un item est "actif"
     * (= il a quitté l’état TODO, donc a été démarré).
     */
    private function isActive($item): bool
    {
        // ✅ États considérés comme "inactifs"
        $etatsInactifs = ['TODO', 'IN_PROGRESS', 'REVISION_NECESSAIRE'];

        if (isset($item->realisationTache)) {
            $etat = optional($item->realisationTache?->etatRealisationTache?->workflowTache)->code;
            return $etat !== null && !in_array($etat, $etatsInactifs, true);
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
    private function calculerEtat(RealisationUa $ua): ?string
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
        if (
            $chapitres->count() > 0 &&
            $chapitres->every(fn($c) => optional($c->etatRealisationChapitre)->code === 'TODO')
        ) {
            return 'TODO';
        }

        // 🎯 Cas 2 : Tous chapitres, prototypes, projets = DONE
        $allChapitresDone = $chapitres->every(fn($c) => optional($c->etatRealisationChapitre)->code === 'DONE');
        $allPrototypesDone = $prototypes->isNotEmpty() && $prototypes->every(
            fn($p) =>
            $p->realisationTache?->etatRealisationTache->workflowTache->code === 'APPROVED'
        );
        $allProjetsDone = $projets->isNotEmpty() && $projets->every(
            fn($p) =>
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
        if ($chapitres->contains(fn($c) => optional($c->etatRealisationChapitre)->code === 'IN_PROGRESS')) {
            return 'IN_PROGRESS_CHAPITRE';
        }

        return 'IN_PROGRESS_CHAPITRE';
    }


}
