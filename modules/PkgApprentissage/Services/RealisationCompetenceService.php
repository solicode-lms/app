<?php

namespace Modules\PkgApprentissage\Services;

use Modules\PkgApprentissage\Models\EtatRealisationCompetence;
use Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence;
use Modules\PkgApprentissage\Models\RealisationCompetence;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;
use Modules\PkgApprentissage\Services\Base\BaseRealisationCompetenceService;
use Modules\PkgCompetences\Services\CompetenceService;

use Modules\PkgApprentissage\Services\RealisationModuleService;
 



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
            $rc->progression_ideal_cache = 0;
            $rc->pourcentage_non_valide_cache = 0;
            $rc->taux_rythme_cache = null;
            $rc->save();
            return;
        }

        // ✅ Agrégats sur les micro-compétences
        $totalNote = $rmcs->sum(fn($rmc) => $rmc->note_cache ?? 0);
        $totalBareme = $rmcs->sum(fn($rmc) => $rmc->bareme_cache ?? 0);
        $totalProgression = $rmcs->sum(fn($rmc) => $rmc->progression_cache ?? 0);
        $totalProgressionIdeal = $rmcs->sum(fn($rmc) => $rmc->progression_ideal_cache ?? 0);
        $totalPourcentageNonValide = $rmcs->sum(fn($rmc) => $rmc->pourcentage_non_valide_cache ?? 0);

        // ✅ Progressions
        $rc->progression_cache = round($totalProgression / $totalRmc, 1);
        $rc->progression_ideal_cache = round($totalProgressionIdeal / $totalRmc, 1);
        $rc->pourcentage_non_valide_cache = round($totalPourcentageNonValide / $totalRmc, 1);

        // ✅ Notes & barèmes
        $rc->note_cache = round($totalNote, 2);
        $rc->bareme_cache = round($totalBareme, 2);

        // ✅ Taux de rythme (nullable si progression idéale = 0)
        $rc->taux_rythme_cache = $rc->progression_ideal_cache > 0
            ? round(($rc->progression_cache / $rc->progression_ideal_cache) * 100, 1)
            : null;

        // ✅ Calcul de l’état global
        $nouvelEtatCode = $this->calculerEtatDepuisMicroCompetences($rc);
        if ($nouvelEtatCode) {
            $nouvelEtat = EtatRealisationCompetence::where('code', $nouvelEtatCode)->first();
            if ($nouvelEtat && $rc->etat_realisation_competence_id !== $nouvelEtat->id) {
                $rc->etat_realisation_competence_id = $nouvelEtat->id;
            }
        }

        $rc->dernier_update = now();
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



    /**
     * Récupère ou crée la réalisation d'une compétence pour un apprenant donné.
     *
     * @param  int $apprenantId
     * @param  int $competenceId
     * @return RealisationCompetence
     */
    public function getOrCreateApprenant(int $apprenantId, int $competenceId): RealisationCompetence
    {
        // 1️⃣ Chercher une réalisation de compétence existante
        $rc = $this->model
            ->where('competence_id', $competenceId)
            ->where('apprenant_id', $apprenantId)
            ->first();

        if ($rc) {
            return $rc;
        }

        // 2️⃣ Charger la compétence et son module
        $competence = \Modules\PkgCompetences\Models\Competence::findOrFail($competenceId);

        $moduleId = $competence->module_id ?? null;

        if (! $moduleId) {
            throw new \RuntimeException("Impossible de déterminer le module lié à la compétence #$competenceId");
        }

        // 3️⃣ S'assurer que la réalisation du module existe
        $realisationModuleService = new RealisationModuleService();
        $realisationModuleService->getOrCreateByApprenant($apprenantId, $moduleId);

        // 4️⃣ Rechercher à nouveau (elle a pu être créée par afterCreateRules du module)
        $rc = $this->model
            ->where('competence_id', $competenceId)
            ->where('apprenant_id', $apprenantId)
            ->first();

        // 5️⃣ Si toujours rien, on crée explicitement la RealisationCompetence
        if (! $rc) {
            $rc = $this->create([
                'competence_id' => $competenceId,
                'apprenant_id'  => $apprenantId,
                // etat_realisation_competence_id géré dans create()
            ]);
        }

        return $rc;
    }


}
