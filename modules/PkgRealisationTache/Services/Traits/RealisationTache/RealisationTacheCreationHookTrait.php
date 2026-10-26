<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgApprentissage\Models\RealisationChapitre;
use Modules\PkgCreationTache\Models\Tache;

/**
 * Trait dédié aux règles et hooks du cycle de vie de création (beforeCreateRules, afterCreateRules)
 * pour RealisationTache.
 */
trait RealisationTacheCreationHookTrait
{
    /**
     * Règle métier exécutée avant la création d'une RealisationTache.
     * 1. Détermine automatiquement `tache_affectation_id` si manquant.
     * 2. Ajuste `etat_realisation_tache_id` si le chapitre est déjà validé.
     *
     * @param array $data
     * @return void
     */
    public function beforeCreateRules(array &$data): void
    {
        // Règle : Assigner la note du projet d'origine si la tâche a projet_origine_note_id
        if (!empty($data['tache_id']) && !empty($data['realisation_projet_id'])) {
            $tache = Tache::find($data['tache_id']);
            if ($tache && !empty($tache->projet_origine_note_id)) {
                $realisationProjet = RealisationProjet::find($data['realisation_projet_id']);
                if ($realisationProjet) {
                    $apprenantId = $realisationProjet->apprenant_id;
                    $projetOrigineNoteId = $tache->projet_origine_note_id;

                    $realisationProjetOrigine = RealisationProjet::where('apprenant_id', $apprenantId)
                        ->whereHas('affectationProjet', function ($q) use ($projetOrigineNoteId) {
                            $q->where('projet_id', $projetOrigineNoteId);
                        })->first();

                    if ($realisationProjetOrigine) {
                        $data['note'] = $realisationProjetOrigine->calculerNoteAvecEchelle();
                    }
                }
            }
        }

        // 🛡️ Plafonnement de la note par rapport au barème de la tâche
        if (isset($data['note']) && !empty($data['tache_id'])) {
            $tache = Tache::find($data['tache_id']);
            if ($tache && $tache->note !== null && $data['note'] > $tache->note) {
                $data['note'] = $tache->note;
            }
        }

        // 1. Règle métier : Lien avec l'affectation de groupe (TacheAffectation)
        if (empty($data['tache_affectation_id']) && !empty($data['tache_id']) && !empty($data['realisation_projet_id'])) {
            $tache = Tache::find($data['tache_id']);
            $realisationProjet = RealisationProjet::find($data['realisation_projet_id']);

            if ($tache && $realisationProjet && $realisationProjet->affectation_projet_id) {
                $tacheAffectationService = new \Modules\PkgRealisationTache\Services\TacheAffectationService();
                $tacheAffectation = $tacheAffectationService->getOrCreateTacheAffectation($tache, $realisationProjet->affectationProjet);
                $data['tache_affectation_id'] = $tacheAffectation->id;
            }
        }

        // 2. Règle métier : Si le chapitre lié est déjà validé (DONE) pour l'apprenant
        if ($this->shouldSkipCreationIfChapitreDone($data)) {
            $etatApproved = \Modules\PkgRealisationTache\Models\EtatRealisationTache::whereHas('workflowTache', function ($q) {
                $q->where('code', 'APPROVED');
            })->first();

            if ($etatApproved) {
                $data['etat_realisation_tache_id'] = $etatApproved->id;
                $data['date_fin'] = now();
                $data['date_debut'] = $data['date_debut'] ?? now();
            }
        }
    }

    /**
     * Règles à appliquer après la création d'une RealisationTache.
     *
     * @param mixed $item L'instance de RealisationTache créée.
     * @return void
     */
    public function afterCreateRules($item): void
    {
        if (!($item instanceof RealisationTache)) {
            return;
        }

        $realisationTache = $item;

        // Chargement des relations nécessaires
        $realisationTache->loadMissing([
            'tache.chapitre',
            'realisationProjet.affectationProjet.projet.mobilisationUas',
            'realisationProjet.apprenant'
        ]);

        $tache = $realisationTache->tache;
        $realisationProjet = $realisationTache->realisationProjet;

        $realisationUaService = new RealisationUaService();
        $realisationChapitreService = app(RealisationChapitreService::class);

        // 🔗 Si le chapitre existe, on lie ou crée sa RealisationChapitre
        if ($tache && $tache->chapitre && $realisationProjet) {
            $realisationUA = $realisationUaService->getOrCreateApprenant(
                $realisationProjet->apprenant_id,
                $tache->chapitre->unite_apprentissage_id
            );

            $chapitreExistant = RealisationChapitre::where('chapitre_id', $tache->chapitre->id)
                ->where('realisation_ua_id', $realisationUA->id)
                ->first();

            if (isset($chapitreExistant) && $chapitreExistant) {
                if ($chapitreExistant->etatRealisationChapitre?->code !== 'DONE') {
                    $chapitreExistant->update([
                        'realisation_tache_id' => $realisationTache->id,
                    ]);
                } else {
                    $chapitreExistant->loadMissing('realisationTache.tache', 'realisationTache.realisationProjet.affectationProjet.projet');
                    $tacheTitre = $chapitreExistant->realisationTache?->tache?->titre ?? 'N/A';
                    $projetTitre = $chapitreExistant->realisationTache?->realisationProjet?->affectationProjet?->projet?->titre ?? 'N/A';

                    $realisationTache->update([
                        'remarques_formateur' => "Ce chapitre est déjà validé dans la tâche : [{$tacheTitre}] du projet : [{$projetTitre}]."
                    ]);
                }
            } else {
                $realisationChapitreService->create([
                    'realisation_tache_id' => $realisationTache->id,
                    'chapitre_id' => $tache->chapitre->id,
                    'realisation_ua_id' => $realisationUA->id,
                ]);
            }
        }

        // 🧩 Gestion consolidée des Compétences (N2/N3) via ActionsTrait
        $this->syncRealisationPrototypeEtProjetAvecMobilisations($realisationTache);

        // 🎯 Mise à jour du pourcentage de réalisation dans TacheAffectation
        if ($realisationTache->tache_affectation_id) {
            $tacheAffectationService = new \Modules\PkgRealisationTache\Services\TacheAffectationService();
            $tacheAffectation = $realisationTache->tacheAffectation ?? \Modules\PkgRealisationTache\Models\TacheAffectation::find($realisationTache->tache_affectation_id);
            if ($tacheAffectation) {
                $tacheAffectationService->mettreAjourTacheProgression($tacheAffectation);
            }
        }

        // Mettre à jour la réalisation de projet, les UAs et les chapitres si l'état ou la note est fourni(e) pendant la création
        if ($realisationTache->note !== null || !empty($realisationTache->etat_realisation_tache_id)) {
            if ($realisationProjet) {
                $realisationProjetService = app(\Modules\PkgRealisationProjets\Services\RealisationProjetService::class);
                $realisationProjetService->mettreAJourEtatDepuisRealisationTaches($realisationProjet);
                $realisationProjetService->mettreAJourProgressionDepuisEtatDesTaches($realisationProjet);
                if ($realisationTache->note !== null) {
                    $realisationProjetService->calculerNoteEtBaremeDepuisTaches($realisationProjet);
                }
            }

            $realisationTache->loadMissing(['realisationUaPrototypes', 'realisationUaProjets', 'realisationChapitres']);

            $uaIds = collect();
            foreach ($realisationTache->realisationUaPrototypes as $proto) {
                if ($proto->realisation_ua_id) {
                    $uaIds->push($proto->realisation_ua_id);
                }
            }
            foreach ($realisationTache->realisationUaProjets as $protoProjet) {
                if ($protoProjet->realisation_ua_id) {
                    $uaIds->push($protoProjet->realisation_ua_id);
                }
            }
            $uaIds = $uaIds->unique()->filter();

            if ($uaIds->isNotEmpty()) {
                $realisationUaService = new RealisationUaService();
                $uas = \Modules\PkgApprentissage\Models\RealisationUa::whereIn('id', $uaIds)->get();
                foreach ($uas as $ua) {
                    $realisationUaService->calculerProgression($ua);
                }
            }

            if (!$realisationTache->realisationChapitres->isEmpty()) {
                $realisationChapitreService = new RealisationChapitreService();
                $realisationChapitreService->calculerProgression($realisationTache);
            }

            if ($realisationTache->tacheAffectation) {
                $tacheAffectationService = app(\Modules\PkgRealisationTache\Services\TacheAffectationService::class);
                $tacheAffectationService->mettreAjourTacheProgression($realisationTache->tacheAffectation);
                $tacheAffectationService->lancerLiveCodingSiEligible($realisationTache->tacheAffectation);
            }
        }
    }
}
