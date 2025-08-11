<?php

namespace Modules\PkgApprentissage\Services;

use Modules\PkgApprentissage\Models\EtatRealisationModule;
use Modules\PkgApprentissage\Models\RealisationModule;
use Modules\PkgApprentissage\Services\Base\BaseRealisationModuleService;

class RealisationModuleService extends BaseRealisationModuleService
{
    /**
     * Création avec état par défaut si non fourni
     */
    public function create(array|object $data)
    {
        $data = (array) $data;

        if (empty($data['etat_realisation_module_id'])) {
            $ordreEtatInitial = EtatRealisationModule::min('ordre');
            $data['etat_realisation_module_id'] = EtatRealisationModule::where('ordre', $ordreEtatInitial)->value('id');
        }

        return parent::create($data);
    }

    /**
     * Récupère ou crée une réalisation de module pour un apprenant
     */
    public function getOrCreateByApprenant(int $apprenantId, int $moduleId): RealisationModule
    {
        $realisation = $this->model
            ->where('apprenant_id', $apprenantId)
            ->where('module_id', $moduleId)
            ->first();

        if ($realisation) {
            return $realisation;
        }

        $ordreEtatInitial = EtatRealisationModule::min('ordre');
        $etatId = EtatRealisationModule::where('ordre', $ordreEtatInitial)->value('id');

        return $this->create([
            'apprenant_id'                => $apprenantId,
            'module_id'                   => $moduleId,
            'etat_realisation_module_id'  => $etatId,
            'date_debut'                  => now(),
        ]);
    }

    /**
     * Calculer la progression d'un module depuis ses compétences
     */
    public function calculerProgression(RealisationModule $rm): void
    {
        $rm->load('realisationCompetences');

        $competences = $rm->realisationCompetences;
        $totalComp = $competences->count();

        if ($totalComp === 0) {
            $rm->progression_cache = 0;
            $rm->note_cache = 0;
            $rm->bareme_cache = 0;
            $rm->save();
            return;
        }

        $totalNote = $competences->sum(fn($c) => $c->note_cache ?? 0);
        $totalBareme = $competences->sum(fn($c) => $c->bareme_cache ?? 0);
        $totalProgression = $competences->sum(fn($c) => $c->progression_cache ?? 0);

        $rm->progression_cache = round($totalProgression / $totalComp, 1);
        $rm->note_cache = round($totalNote, 2);
        $rm->bareme_cache = round($totalBareme, 2);

        // Calcul de l’état global du module
        $nouvelEtatCode = $this->calculerEtatDepuisCompetences($rm);
        if ($nouvelEtatCode) {
            $nouvelEtat = EtatRealisationModule::where('code', $nouvelEtatCode)->first();
            if ($nouvelEtat && $rm->etat_realisation_module_id !== $nouvelEtat->id) {
                $rm->etat_realisation_module_id = $nouvelEtat->id;
            }
        }

        $rm->saveQuietly();

        // Ici on pourrait recalculer la progression d'un niveau supérieur (parcours, bloc, etc.)
    }

    /**
     * Déterminer l'état d'un module en fonction de ses compétences
     */
    public function calculerEtatDepuisCompetences(RealisationModule $rm): ?string
    {
        $competences = $rm->realisationCompetences;

        if ($competences->isEmpty()) {
            return 'TODO';
        }

        // Récupérer les codes d'état des compétences
        $codesComp = $competences
            ->load('etatRealisationCompetence')
            ->pluck('etatRealisationCompetence.code')
            ->filter()
            ->unique()
            ->toArray();

        /**
         * 🎯 Mapping des états compétences → états modules
         * (en respectant la hiérarchie d'avancement et blocage)
         */
        $mapping = [
            'PAUSED'                  => 'PAUSED',
            'IN_PROGRESS_CHAPITRE'    => 'IN_PROGRESS_INTRO',
            'IN_PROGRESS_PROTOTYPE'   => 'IN_PROGRESS_INTERMEDIAIRE',
            'IN_PROGRESS_PROJET'      => 'IN_PROGRESS_AVANCE',
            'TODO'                    => 'TODO',
            'DONE'                    => 'DONE',
        ];

        // Priorité des états module
        $priorites = [
            'PAUSED',
            'IN_PROGRESS_INTRO',
            'IN_PROGRESS_INTERMEDIAIRE',
            'IN_PROGRESS_AVANCE',
            'TODO',
            'DONE',
        ];

        // Traduire les états compétences vers états modules
        $codesModule = [];
        foreach ($codesComp as $codeComp) {
            if (isset($mapping[$codeComp])) {
                $codesModule[] = $mapping[$codeComp];
            }
        }

        // Retourner le premier état trouvé selon la priorité
        foreach ($priorites as $code) {
            if (in_array($code, $codesModule, true)) {
                return $code;
            }
        }

        return 'TODO';
    }

}
