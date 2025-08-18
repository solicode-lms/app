<?php

namespace Modules\PkgApprentissage\Services;

use Modules\PkgApprentissage\Models\EtatRealisationCompetence;
use Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence;
use Modules\PkgApprentissage\Models\RealisationCompetence;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;
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
            $data['etat_realisation_competence_id'] = EtatRealisationCompetence::where('code', 'TODO')->first()->id;
        }

        return parent::create($data);
    }

    protected function afterCreateRules($realisationCompetence, $id): void
    {
        // 🔎 Récupérer les micro-compétences de la compétence
        $microCompetences = $realisationCompetence->competence?->microCompetences ?? collect();
        if ($microCompetences->isEmpty()) {
            return;
        }

        // ✅ État par défaut "TODO"
        $etatTodoMicro = EtatRealisationMicroCompetence::where('code', 'TODO')->first();
        $realisationMicroService = new RealisationMicroCompetenceService();

        foreach ($microCompetences as $micro) {
            $exists = RealisationMicroCompetence::where('realisation_competence_id', $realisationCompetence->id)
                ->where('micro_competence_id', $micro->id)
                ->where('apprenant_id', $realisationCompetence->apprenant_id)
                ->exists();

            if (!$exists) {
                $realisationMicroService->create([
                    'realisation_competence_id'            => $realisationCompetence->id,
                    'micro_competence_id'                  => $micro->id,
                    'apprenant_id'                         => $realisationCompetence->apprenant_id,
                    'etat_realisation_micro_competence_id' => $etatTodoMicro?->id,
                ]);
            }
        }
    }

    /**
     * Récupère ou crée une réalisation de compétence pour un apprenant
     */
    // public function getOrCreateByApprenant(int $apprenantId, int $competenceId): RealisationCompetence
    // {
    //     // 🔍 Recherche si déjà existant
    //     $realisation = $this->model
    //         ->where('apprenant_id', $apprenantId)
    //         ->where('competence_id', $competenceId)
    //         ->first();

    //     if ($realisation) {
    //         return $realisation;
    //     }

    //     // 📌 Charger la compétence pour retrouver le module parent
    //     $competence = \Modules\PkgCompetences\Models\Competence::with('module')
    //         ->findOrFail($competenceId);

    //     // 🆕 Récupérer ou créer la réalisation de module associée
    //     $realisationModuleService = new RealisationModuleService();
    //     $realisationModule = $realisationModuleService->getOrCreateByApprenant(
    //         $apprenantId,
    //         $competence->module_id
    //     );

    //     // 🎯 État initial
    //     $etatId = EtatRealisationCompetence::where('code', 'TODO')->first()->id;

    //     // 🏗️ Création avec lien vers realisation_module_id
    //     return $this->create([
    //         'apprenant_id' => $apprenantId,
    //         'competence_id' => $competenceId,
    //         'realisation_module_id' => $realisationModule->id, // ✅ non nullable
    //         'etat_realisation_competence_id' => $etatId,
    //         'date_debut' => now(),
    //     ]);
    // }


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
