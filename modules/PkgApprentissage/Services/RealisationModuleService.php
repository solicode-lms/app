<?php

namespace Modules\PkgApprentissage\Services;

use Modules\PkgApprentissage\Models\EtatRealisationCompetence;
use Modules\PkgApprentissage\Models\EtatRealisationModule;
use Modules\PkgApprentissage\Models\RealisationCompetence;
use Modules\PkgApprentissage\Models\RealisationModule;
use Modules\PkgApprentissage\Services\Base\BaseRealisationModuleService;

class RealisationModuleService extends BaseRealisationModuleService
{
    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationModule');
        $this->fieldsFilterable = [];
        
        $filiereService = new \Modules\PkgFormation\Services\FiliereService();
        $filiereIds = $this->getAvailableFilterValues('Module.Filiere_id');
        $filieres = $filiereService->getByIds($filiereIds);

        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgFormation::filiere.plural"),
            'Module.Filiere_id', 
            \Modules\PkgFormation\Models\Filiere::class,
            "id", 
            "id",
            $filieres,
            "[name='module_id'],[name='Apprenant.groupes.id']",
            route('modules.getData') . ',' . route('groupes.getData'),
            "filiere_id,filiere_id"
        );
    
        if (!array_key_exists('module_id', $scopeVariables)) {
            $moduleService = new \Modules\PkgFormation\Services\ModuleService();
            
            // 🎯 OVERRIDE : Filtrer les modules enseignés par le formateur connecté
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user && $user->hasRole('formateur') && $user->formateurs()->exists()) {
                $formateur = $user->formateurs()->first();
             
                // 🎯 Ajout du scope dans le viewState pour filtrer dynamiquement lors des appels AJAX (getData)
                $this->viewState->set('scope.module.competences.microCompetences.uniteApprentissages.mobilisationUas.projet.formateur_id', $formateur->id);

                // Modules dont les UA sont mobilisées dans les projets créés/liés au formateur
                $moduleIds = \Modules\PkgFormation\Models\Module::whereHas('competences.microCompetences.uniteApprentissages.mobilisationUas.projet', function($query) use ($formateur) {
                    $query->where('formateur_id', $formateur->id);
                })->pluck('id')->toArray();
            } else {
                $moduleIds = $this->getAvailableFilterValues('module_id');
            }

            $modules = $moduleService->getByIds($moduleIds);
 
            $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                __("PkgFormation::module.plural"), 
                'module_id', 
                \Modules\PkgFormation\Models\Module::class, 
                'code',
                $modules
            );
        }
    
        $groupeService = new \Modules\PkgApprenants\Services\GroupeService();
        $groupeIds = $this->getAvailableFilterValues('Apprenant.groupes.id');
        $groupes = $groupeService->getByIds($groupeIds);

        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgApprenants::groupe.plural"),
            'Apprenant.groupes.id', 
            \Modules\PkgApprenants\Models\Groupe::class,
            "id", 
            "id",
            $groupes,
            "[name='apprenant_id']",
            route('apprenants.getData'),
            "groupes.id"
        );
    
        if (!array_key_exists('apprenant_id', $scopeVariables)) {
            $apprenantService = new \Modules\PkgApprenants\Services\ApprenantService();
            $apprenantIds = $this->getAvailableFilterValues('apprenant_id');
            $apprenants = $apprenantService->getByIds($apprenantIds);

            $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                __("PkgApprenants::apprenant.plural"), 
                'apprenant_id', 
                \Modules\PkgApprenants\Models\Apprenant::class, 
                'nom',
                $apprenants
            );
        }
    
        if (!array_key_exists('etat_realisation_module_id', $scopeVariables)) {
            $etatRealisationModuleService = new \Modules\PkgApprentissage\Services\EtatRealisationModuleService();
            $etatRealisationModuleIds = $this->getAvailableFilterValues('etat_realisation_module_id');
            $etatRealisationModules = $etatRealisationModuleService->getByIds($etatRealisationModuleIds);

            $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                __("PkgApprentissage::etatRealisationModule.plural"), 
                'etat_realisation_module_id', 
                \Modules\PkgApprentissage\Models\EtatRealisationModule::class, 
                'code',
                $etatRealisationModules
            );
        }
    }

    /**
     * Création avec état par défaut si non fourni
     */
    public function create(array|object $data)
    {
        $data = (array) $data;

        if (empty($data['etat_realisation_module_id'])) {
            $data['etat_realisation_module_id'] = EtatRealisationModule::where('code', 'TODO')->first()->id;
        }

        return parent::create($data);
    }

     /**
     * Règles exécutées après la création d’un RealisationModule.
     */
    protected function afterCreateRules($realisationModule, $id): void
    {
        // 🔎 Récupérer toutes les compétences liées au module
        $competences = $realisationModule->module?->competences ?? collect();

        if ($competences->isEmpty()) {
            return;
        }

        // ✅ État par défaut "TODO"
        $etatTodo = EtatRealisationCompetence::where('code', 'TODO')->first();
        $realisationCompetenceService = new RealisationCompetenceService();

        foreach ($competences as $competence) {
            $exists = RealisationCompetence::where('realisation_module_id', $realisationModule->id)
                ->where('competence_id', $competence->id)
                ->where('apprenant_id', $realisationModule->apprenant_id) // 🔑 associer l’apprenant
                ->exists();

            if (!$exists) {
                $realisationCompetenceService->create([
                    'realisation_module_id'          => $realisationModule->id,
                    'competence_id'                  => $competence->id,
                    'apprenant_id'                   => $realisationModule->apprenant_id, // ✅ on lie l’apprenant
                    'etat_realisation_competence_id' => $etatTodo?->id,
                ]);
            }
        }
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

        $ordreEtatInitial = EtatRealisationModule::where('code', 'TODO')->first();
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
            $rm->bareme_non_evalue_cache = 0;
            $rm->progression_ideal_cache = 0;
            $rm->pourcentage_non_valide_cache = 0;
            $rm->taux_rythme_cache = null;
            $rm->save();
            return;
        }

        // ✅ Agrégats sur les compétences
        $totalNote = $competences->sum(fn($c) => $c->note_cache ?? 0);
        $totalBareme = $competences->sum(fn($c) => $c->bareme_cache ?? 0);
        $totalBaremeNonEvalue = $competences->sum(fn($c) => $c->bareme_non_evalue_cache ?? 0);
        $totalProgression = $competences->sum(fn($c) => $c->progression_cache ?? 0);
        $totalProgressionIdeal = $competences->sum(fn($c) => $c->progression_ideal_cache ?? 0);
        $totalPourcentageNonValide = $competences->sum(fn($c) => $c->pourcentage_non_valide_cache ?? 0);

        // ✅ Progressions
        $rm->progression_cache = round($totalProgression / $totalComp, 1);
        $rm->progression_ideal_cache = round($totalProgressionIdeal / $totalComp, 1);
        $rm->pourcentage_non_valide_cache = round($totalPourcentageNonValide / $totalComp, 1);

        // ✅ Notes & barèmes
        $rm->note_cache = round($totalNote, 2);
        $rm->bareme_cache = round($totalBareme, 2);
        $rm->bareme_non_evalue_cache = round($totalBaremeNonEvalue, 2);

        // ✅ Taux de rythme (nullable si progression idéale = 0)
        $rm->taux_rythme_cache = $rm->progression_ideal_cache > 0
            ? round(($rm->progression_cache / $rm->progression_ideal_cache) * 100, 1)
            : null;

        // ✅ Calcul de l’état global du module
        $nouvelEtatCode = $this->calculerEtatDepuisCompetences($rm);
        if ($nouvelEtatCode) {
            $nouvelEtat = EtatRealisationModule::where('code', $nouvelEtatCode)->first();
            if ($nouvelEtat && $rm->etat_realisation_module_id !== $nouvelEtat->id) {
                $rm->etat_realisation_module_id = $nouvelEtat->id;
            }
        }

        $rm->dernier_update = now();
        $rm->saveQuietly();
       

        // 🔜 Ici tu pourras recalculer la progression d'un niveau supérieur (parcours, bloc, etc.)
    }


    /**
     * Déterminer l'état d'un module en fonction de ses compétences
     */
    public function calculerEtatDepuisCompetences(RealisationModule $rm): ?string
    {
        $competences = $rm->realisationCompetences()->with('etatRealisationCompetence')->get();

        if ($competences->isEmpty()) {
            return 'TODO';
        }

        // Récupérer les codes d'état des compétences
        $codesComp = $competences
            ->pluck('etatRealisationCompetence.code')
            ->filter()
            ->values();

        // Cas 1 : toutes en TODO → TODO
        if ($codesComp->every(fn($c) => $c === 'TODO')) {
            return 'TODO';
        }

        // Cas 2 : toutes en DONE → DONE
        if ($codesComp->every(fn($c) => $c === 'DONE')) {
            return 'DONE';
        }

        /**
         * 🎯 Mapping des états compétences → états modules
         */
        $mapping = [
            'PAUSED'                  => 'PAUSED',
            'IN_PROGRESS_CHAPITRE'    => 'IN_PROGRESS_INTRO',
            'IN_PROGRESS_PROTOTYPE'   => 'IN_PROGRESS_INTERMEDIAIRE',
            'IN_PROGRESS_PROJET'      => 'IN_PROGRESS_AVANCE',
            'TODO'                    => 'TODO',
            'DONE'                    => 'DONE',
        ];

        // Traduire les états compétences vers états modules
        $codesModule = $codesComp->map(fn($codeComp) => $mapping[$codeComp] ?? null)
            ->filter()
            ->values();

        // Priorité des états module
        $priorites = [
            'PAUSED',
            'IN_PROGRESS_INTRO',
            'IN_PROGRESS_INTERMEDIAIRE',
            'IN_PROGRESS_AVANCE',
            'DONE',
            'TODO',
        ];

        foreach ($priorites as $code) {
            if ($codesModule->contains($code)) {
                return $code;
            }
        }

        return 'IN_PROGRESS_CHAPITRE';
    }


}
