<?php

namespace Modules\PkgApprentissage\Services;

use Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence;
use Modules\PkgApprentissage\Models\EtatRealisationUa;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;
use Modules\PkgApprentissage\Services\Base\BaseRealisationMicroCompetenceService;
use Modules\PkgCompetences\Services\UniteApprentissageService;

/**
 * Classe RealisationMicroCompetenceService pour gérer la persistance de l'entité RealisationMicroCompetence.
 */
class RealisationMicroCompetenceService extends BaseRealisationMicroCompetenceService
{
    

    public function create(array|object $data)
    {
        // Convertir en tableau si $data est un objet
        $data = (array) $data;

        // Vérifier si l'état est fourni, sinon assigner l'état par défaut
        if (empty($data['etat_realisation_micro_competence_id'])) {
            $ordreEtatInitial = EtatRealisationMicroCompetence::min('ordre');
            $data['etat_realisation_micro_competence_id'] = EtatRealisationMicroCompetence::where('ordre', $ordreEtatInitial)->value('id');
        }

        return parent::create($data);
    }


    public function afterCreateRules(RealisationMicroCompetence $realisationMicroCompetence): void
    {
        $realisationUAService = new RealisationUAService();
        $etat_realisation_ua_id = EtatRealisationUa::where('code', "TODO")->value('id');
        $uas = $realisationMicroCompetence->microCompetence->uniteApprentissages;

        foreach ($uas as $ua) {
            // Vérifier si la réalisation UA existe déjà
            $exists = $realisationUAService->model
                ->where('realisation_micro_competence_id', $realisationMicroCompetence->id)
                ->where('unite_apprentissage_id', $ua->id)
                ->exists();

            if (! $exists) {
                $realisationUAService->create([
                    'realisation_micro_competence_id' => $realisationMicroCompetence->id,
                    'unite_apprentissage_id' => $ua->id,
                    'etat_realisation_ua_id' => $etat_realisation_ua_id,
                ]);
            }
        }
    }

  
    /**
     * Récupère ou crée une réalisation de micro-compétence pour un apprenant.
     *
     * @param  int $apprenantId
     * @param  int $microCompetenceId
     * @return RealisationMicroCompetence
     */
    public function getOrCreateByApprenant(int $apprenantId, int $microCompetenceId): RealisationMicroCompetence
    {
        // 1. Chercher si une réalisation existe déjà
        $realisation = $this->model
            ->where('apprenant_id', $apprenantId)
            ->where('micro_competence_id', $microCompetenceId)
            ->first();

        if ($realisation) {
            return $realisation;
        }

        // 2. Créer une nouvelle réalisation avec l'état initial
        $ordreEtatInitial = EtatRealisationMicroCompetence::min('ordre');
        $etatRealisationId = EtatRealisationMicroCompetence::where('ordre', $ordreEtatInitial)->value('id');

        return $this->create([
            'apprenant_id'                    => $apprenantId,
            'micro_competence_id'             => $microCompetenceId,
            'etat_realisation_micro_competence_id' => $etatRealisationId,
            'date_debut' => now(),
        ]);
    }

    public function afterUpdateRules(RealisationMicroCompetence $rmc): void
    {
        $this->calculerProgression($rmc);
    }
    public function calculerProgression(RealisationMicroCompetence $rmc): void
    {
        $rmc->load('realisationUas');

        $uas = $rmc->realisationUas;
        $totalUa = $uas->count();

        if ($totalUa === 0) {
            $rmc->progression_cache = 0;
            $rmc->note_cache = 0;
            $rmc->bareme_cache = 0;
            $rmc->save();
            return;
        }

        $totalNote = $uas->sum(fn($ua) => $ua->note_cache ?? 0);
        $totalBareme = $uas->sum(fn($ua) => $ua->bareme_cache ?? 0);
        $totalProgression = $uas->sum(fn($ua) => $ua->progression_cache ?? 0);

        $rmc->progression_cache = round($totalProgression / $totalUa, 1);
        $rmc->note_cache = round($totalNote, 2);
        $rmc->bareme_cache = round($totalBareme, 2);


        // Calcul de l’état global de la micro-compétence
        $nouvelEtatCode = $this->calculerEtatDepuisUas($rmc);
        if ($nouvelEtatCode) {
            $nouvelEtat = EtatRealisationMicroCompetence::where('code', $nouvelEtatCode)->first();
            if ($nouvelEtat && $rmc->etat_realisation_micro_competence_id !== $nouvelEtat->id) {
                $rmc->etat_realisation_micro_competence_id = $nouvelEtat->id;
            }
        }

        $rmc->saveQuietly();

        // 🔹 Calcul progression RealisationCompetence
        if ($rmc->microCompetence && $rmc->microCompetence->competence) {
            $realisationCompetenceService = new RealisationCompetenceService();
            $realisationCompetence = $realisationCompetenceService->getOrCreateByApprenant(
                $rmc->apprenant_id,
                $rmc->microCompetence->competence_id
            );
            $realisationCompetenceService->calculerProgression($realisationCompetence);
        }



    }

    /**
     * Calcule l’état global d’une micro-compétence selon l’avancement de ses UAs.
     *
     * Progression stricte :
     * - Si au moins une UA a un chapitre non terminé → IN_PROGRESS_CHAPITRE
     * - Sinon, si au moins une UA a un prototype non terminé → IN_PROGRESS_PROTOTYPE
     * - Sinon, si au moins une UA a un projet non terminé → IN_PROGRESS_PROJET
     * - Sinon (tout est terminé) → DONE
     *
     * @param RealisationMicroCompetence $rmc
     * @return string|null
     */
    public function calculerEtatDepuisUas(RealisationMicroCompetence $rmc): ?string
    {
        $uas = $rmc->realisationUas;

        if ($uas->isEmpty()) {
            return 'TODO';
        }

        $uas->load([
            'realisationChapitres.etatRealisationChapitre',
            'realisationUaPrototypes.realisationTache.etatRealisationTache',
            'realisationUaProjets.realisationTache.etatRealisationTache',
        ]);

        foreach ($uas as $ua) {
            if ($ua->realisationChapitres->contains(fn($c) =>
                optional($c->etatRealisationChapitre)->code !== 'DONE'
            )) {
                return 'IN_PROGRESS_CHAPITRE';
            }
        }

        foreach ($uas as $ua) {
            if ($ua->realisationUaPrototypes->contains(fn($p) =>
                $p->realisationTache?->etatRealisationTache->workflowTache->code !== 'APPROVED'
            )) {
                return 'IN_PROGRESS_PROTOTYPE';
            }
        }

        foreach ($uas as $ua) {
            if ($ua->realisationUaProjets->contains(fn($p) =>
                $p->realisationTache?->etatRealisationTache->workflowTache->code !== 'APPROVED'
            )) {
                return 'IN_PROGRESS_PROJET';
            }
        }

        return 'DONE';
    }



}
