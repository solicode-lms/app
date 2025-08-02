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
    public function dataCalcul($realisationMicroCompetence)
    {
        // En Cas d'édit
        if(isset($realisationMicroCompetence->id)){
          
        }
      
        return $realisationMicroCompetence;
    }

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
        $this->calculerProgressionEtNote($rmc);
    }
    public function calculerProgressionEtNote(RealisationMicroCompetence $rmc): void
    {
        $rmc->loadMissing('realisationUas');

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

        $rmc->save();


    }

    public function calculerEtatDepuisUas(RealisationMicroCompetence $rmc): ?string
    {
        $uas = $rmc->realisationUas;

        if ($uas->isEmpty()) {
            return 'TODO';
        }

        $etatCodes = $uas->pluck('etatRealisationUa.code')->filter()->unique();

        // 🎯 Toutes les UA sont terminées
        if ($etatCodes->count() === 1 && $etatCodes->first() === 'DONE') {
            return 'DONE';
        }

        // 🎯 Présence de mini-projets (niveau 3)
        if ($etatCodes->contains('IN_PROGRESS_PROJET')) {
            return 'IN_PROGRESS_PROJET';
        }

        // 🎯 Présence de prototypes (niveau 2)
        if ($etatCodes->contains('IN_PROGRESS_PROTOTYPE')) {
            return 'IN_PROGRESS_PROTOTYPE';
        }

        // 🎯 Présence de chapitres (niveau 1)
        if ($etatCodes->contains('IN_PROGRESS_CHAPITRE')) {
            return 'IN_PROGRESS_CHAPITRE';
        }

        // 🎯 Si tout est encore non commencé
        if ($etatCodes->every(fn($code) => $code === 'TODO')) {
            return 'TODO';
        }

        // 🔁 Cas par défaut (au moins un en cours sans correspondance claire)
        return 'IN_PROGRESS_CHAPITRE';
    }



}
