<?php

namespace Modules\PkgApprentissage\Services;

use Modules\PkgApprentissage\Models\EtatRealisationCompetence;
use Modules\PkgApprentissage\Models\RealisationCompetence;
use Modules\PkgApprentissage\Services\Base\BaseRealisationCompetenceService;
use Modules\PkgCompetences\Services\CompetenceService;

class RealisationCompetenceService extends BaseRealisationCompetenceService
{
    /**
     * Création avec état par défaut si non fourni
     */
    public function create(array|object $data)
    {
        $data = (array) $data;

        if (empty($data['etat_realisation_competence_id'])) {
            $ordreEtatInitial = EtatRealisationCompetence::min('ordre');
            $data['etat_realisation_competence_id'] = EtatRealisationCompetence::where('ordre', $ordreEtatInitial)->value('id');
        }

        return parent::create($data);
    }

    /**
     * Récupère ou crée une réalisation de compétence pour un apprenant
     */
    public function getOrCreateByApprenant(int $apprenantId, int $competenceId): RealisationCompetence
    {
        $realisation = $this->model
            ->where('apprenant_id', $apprenantId)
            ->where('competence_id', $competenceId)
            ->first();

        if ($realisation) {
            return $realisation;
        }

        $ordreEtatInitial = EtatRealisationCompetence::min('ordre');
        $etatId = EtatRealisationCompetence::where('ordre', $ordreEtatInitial)->value('id');

        return $this->create([
            'apprenant_id' => $apprenantId,
            'competence_id' => $competenceId,
            'etat_realisation_competence_id' => $etatId,
            'date_debut' => now(),
        ]);
    }

    /**
     * Calculer la progression d'une compétence depuis ses micro-compétences
     */
    public function calculerProgression(RealisationCompetence $rc): void
    {
        $rc->load('realisationMicroCompetences');

        $rmcs = $rc->realisationMicroCompetences;
        $totalRmc = $rmcs->count();

        if ($totalRmc === 0) {
            $rc->progression_cache = 0;
            $rc->note_cache = 0;
            $rc->bareme_cache = 0;
            $rc->save();
            return;
        }

        $totalNote = $rmcs->sum(fn($rmc) => $rmc->note_cache ?? 0);
        $totalBareme = $rmcs->sum(fn($rmc) => $rmc->bareme_cache ?? 0);
        $totalProgression = $rmcs->sum(fn($rmc) => $rmc->progression_cache ?? 0);

        $rc->progression_cache = round($totalProgression / $totalRmc, 1);
        $rc->note_cache = round($totalNote, 2);
        $rc->bareme_cache = round($totalBareme, 2);

        // Calcul de l’état global de la compétence
        $nouvelEtatCode = $this->calculerEtatDepuisMicroCompetences($rc);
        if ($nouvelEtatCode) {
            $nouvelEtat = EtatRealisationCompetence::where('code', $nouvelEtatCode)->first();
            if ($nouvelEtat && $rc->etat_realisation_competence_id !== $nouvelEtat->id) {
                $rc->etat_realisation_competence_id = $nouvelEtat->id;
            }
        }

        $rc->saveQuietly();

         // 🔹 Calcul progression RealisationModule
        if ($rc->competence && $rc->competence->module) {
            $realisationModuleService = new RealisationModuleService();
            $realisationModule = $realisationModuleService->getOrCreateByApprenant(
                $rc->apprenant_id,
                $rc->competence->module_id
            );
            $realisationModuleService->calculerProgression($realisationModule);
        }
    }


    /**
     * Déterminer l'état d'une compétence en fonction de ses micro-compétences
     */
    public function calculerEtatDepuisMicroCompetences(RealisationCompetence $rc): ?string
    {
        $microCompetences = $rc->realisationMicroCompetences;

        if ($microCompetences->isEmpty()) {
            return 'TODO';
        }

        // Charger les états pour éviter les requêtes multiples
        $codesMicro = $microCompetences
            ->load('etatRealisationMicroCompetence')
            ->pluck('etatRealisationMicroCompetence.code')
            ->filter()
            ->unique()
            ->toArray();

        // Ordre de priorité des états
        $priorites = [
            'PAUSED',
            'BLOCKED',
            'IN_PROGRESS_CHAPITRE',
            'IN_PROGRESS_PROTOTYPE',
            'IN_PROGRESS_PROJET',
            'TODO',
            'DONE',
        ];

        // Retourne le premier code présent selon l’ordre de priorité
        foreach ($priorites as $code) {
            if (in_array($code, $codesMicro, true)) {
                return $code;
            }
        }

        // Si rien trouvé, on considère comme TODO
        return 'TODO';
    }


}
