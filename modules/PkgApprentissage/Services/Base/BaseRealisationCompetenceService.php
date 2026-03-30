<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationCompetence;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe RealisationCompetenceService pour gérer la persistance de l'entité RealisationCompetence.
 */
class BaseRealisationCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationCompetences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'competence_id',
        'realisation_module_id',
        'apprenant_id',
        'progression_cache',
        'note_cache',
        'etat_realisation_competence_id',
        'bareme_cache',
        'bareme_non_evalue_cache',
        'dernier_update',
        'commentaire_formateur',
        'date_debut',
        'date_fin',
        'progression_ideal_cache',
        'taux_rythme_cache',
        'pourcentage_non_valide_cache'
    ];



    public function editableFieldsByRoles(): array
    {
        return [
        
        ];
    }


    /**
     * Renvoie les champs de recherche disponibles.
     *
     * @return array
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldsSearchable;
    }

    /**
     * Constructeur de la classe RealisationCompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationCompetence());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::realisationCompetence.plural');
    }


    /**
     * Applique les calculs dynamiques sur les champs marqués avec l’attribut `data-calcule`
     * pendant l’édition ou la création d’une entité.
     *
     * Cette méthode est utilisée dans les formulaires dynamiques pour recalculer certains champs
     * (ex : note, barème, état, progression...) en fonction des valeurs saisies ou modifiées.
     *
     * Elle est déclenchée automatiquement lorsqu’un champ du formulaire possède l’attribut `data-calcule`.
     *
     * @param mixed $data Données en cours d’édition (array ou modèle hydraté sans persistance).
     * @return mixed L’entité enrichie avec les champs recalculés.
     */
    public function dataCalcul($data)
    {
        // 🧾 Chargement ou initialisation de l'entité
        if (!empty($data['id'])) {
            $realisationCompetence = $this->find($data['id']);
            $realisationCompetence->fill($data);
        } else {
            $realisationCompetence = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($realisationCompetence->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $realisationCompetence->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($realisationCompetence->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($realisationCompetence->id, $data);
            }
        }

        return $realisationCompetence;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationCompetence');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('competence_id', $scopeVariables)) {


                    $competenceService = new \Modules\PkgCompetences\Services\CompetenceService();
                    $competenceIds = $this->getAvailableFilterValues('competence_id');
                    $competences = $competenceService->getByIds($competenceIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::competence.plural"), 
                        'competence_id', 
                        \Modules\PkgCompetences\Models\Competence::class, 
                        'code',
                        $competences
                    );
                }
            
            
                if (!array_key_exists('realisation_module_id', $scopeVariables)) {


                    $realisationModuleService = new \Modules\PkgApprentissage\Services\RealisationModuleService();
                    $realisationModuleIds = $this->getAvailableFilterValues('realisation_module_id');
                    $realisationModules = $realisationModuleService->getByIds($realisationModuleIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprentissage::realisationModule.plural"), 
                        'realisation_module_id', 
                        \Modules\PkgApprentissage\Models\RealisationModule::class, 
                        'id',
                        $realisationModules
                    );
                }
            
            
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
            
            
                if (!array_key_exists('etat_realisation_competence_id', $scopeVariables)) {


                    $etatRealisationCompetenceService = new \Modules\PkgApprentissage\Services\EtatRealisationCompetenceService();
                    $etatRealisationCompetenceIds = $this->getAvailableFilterValues('etat_realisation_competence_id');
                    $etatRealisationCompetences = $etatRealisationCompetenceService->getByIds($etatRealisationCompetenceIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprentissage::etatRealisationCompetence.plural"), 
                        'etat_realisation_competence_id', 
                        \Modules\PkgApprentissage\Models\EtatRealisationCompetence::class, 
                        'code',
                        $etatRealisationCompetences
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de realisationCompetence.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array|object $data)
    {
        return parent::create($data);
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getRealisationCompetenceStats(): array
    {

        $stats = $this->initStats();

        // Ajouter les statistiques du propriétaire
        //$contexteState = $this->getContextState();
        // if ($contexteState !== null) {
        //     $stats[] = $contexteState;
        // }
        

        return $stats;
    }

    public function getContextState()
    {
        $value = $this->viewState->generateTitleFromVariables();
        return [
                "icon" => "fas fa-filter",
                "label" => "Filtre",
                "value" =>  $value
        ];
    }



    /**
     * Retourne les types de vues disponibles pour l'index (ex: table, widgets...)
     */
    public function getViewTypes(): array
    {
        return [
            [
                'type'  => 'table',
                'label' => 'Vue Tableau',
                'icon'  => 'fa-table',
            ],
        ];
    }

    /**
     * Retourne le nom de la vue partielle selon le type de vue sélectionné
     */
    public function getPartialViewName(string $viewType): string
    {
        return match ($viewType) {
            'table' => 'PkgApprentissage::realisationCompetence._table',
            default => 'PkgApprentissage::realisationCompetence._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationCompetence_view_type', $default_view_type);
        $realisationCompetence_viewType = $this->viewState->get('realisationCompetence_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationCompetence_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationCompetence.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationCompetence.visible");
        }
        
        // Récupération des données
        $realisationCompetences_data = $this->paginate($params);
        $realisationCompetences_stats = $this->getrealisationCompetenceStats();
        $realisationCompetences_total = $this->count();
        $realisationCompetences_filters = $this->getFieldsFilterable();
        $realisationCompetence_instance = $this->createInstance();
        $realisationCompetence_viewTypes = $this->getViewTypes();
        $realisationCompetence_partialViewName = $this->getPartialViewName($realisationCompetence_viewType);
        $realisationCompetence_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationCompetence.stats', $realisationCompetences_stats);
    
        $realisationCompetences_permissions = [

            'edit-realisationCompetence' => Auth::user()->can('edit-realisationCompetence'),
            'destroy-realisationCompetence' => Auth::user()->can('destroy-realisationCompetence'),
            'show-realisationCompetence' => Auth::user()->can('show-realisationCompetence'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationCompetences_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationCompetences_data as $item) {
                $realisationCompetences_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationCompetence_viewTypes',
            'realisationCompetence_viewType',
            'realisationCompetences_data',
            'realisationCompetences_stats',
            'realisationCompetences_total',
            'realisationCompetences_filters',
            'realisationCompetence_instance',
            'realisationCompetence_title',
            'contextKey',
            'realisationCompetences_permissions',
            'realisationCompetences_permissionsByItem'
        );
    
        return [
            'realisationCompetences_data' => $realisationCompetences_data,
            'realisationCompetences_stats' => $realisationCompetences_stats,
            'realisationCompetences_total' => $realisationCompetences_total,
            'realisationCompetences_filters' => $realisationCompetences_filters,
            'realisationCompetence_instance' => $realisationCompetence_instance,
            'realisationCompetence_viewType' => $realisationCompetence_viewType,
            'realisationCompetence_viewTypes' => $realisationCompetence_viewTypes,
            'realisationCompetence_partialViewName' => $realisationCompetence_partialViewName,
            'contextKey' => $contextKey,
            'realisationCompetence_compact_value' => $compact_value,
            'realisationCompetences_permissions' => $realisationCompetences_permissions,
            'realisationCompetences_permissionsByItem' => $realisationCompetences_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $realisationCompetence_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $realisationCompetence_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($realisationCompetence_ids as $id) {
            $realisationCompetence = $this->find($id);
            $this->authorize('update', $realisationCompetence);
    
            $allFields = $this->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $valeursChamps[$field]])
                ->toArray();
    
            if (!empty($data)) {
                $this->updateOnlyExistanteAttribute($id, $data);
            }

            $jobManager->tick();
            
        }

        return "done";
    }

    /**
    * Liste des champs autorisés à l’édition inline
    */
    public function getInlineFieldsEditable(): array
    {
        // Champs considérés comme inline
        $inlineFields = [
            'competence_id',
            'progression_cache',
            'note_cache'
        ];

        // Récupération des champs autorisés par rôle via getFieldsEditable()
        return array_values(array_intersect(
            $inlineFields,
            $this->getFieldsEditable()
        ));
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(RealisationCompetence $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgApprentissage\App\Requests\RealisationCompetenceRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'realisation_competence',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'competence_id':
                 $values = (new \Modules\PkgCompetences\Services\CompetenceService())
                    ->getAllForSelect($e->competence)
                    ->map(fn($entity) => [
                        'value' => (int) $entity->id,
                        'label' => (string) $entity,
                    ])
                    ->toArray();

                return $this->computeFieldMeta($e, $field, $meta, 'select', [
                    'required' => true,
                    'options'  => [
                        'source' => 'static',
                        'values' => $values,
                    ],
                ]);
            case 'progression_cache':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            case 'note_cache':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(RealisationCompetence $e, array $changes): RealisationCompetence
    {
        $allowed = $this->getInlineFieldsEditable();
        $filtered = Arr::only($changes, $allowed);

        if (empty($filtered)) {
            abort(422, 'Aucun champ autorisé.');
        }

        $rules = [];
        foreach ($filtered as $field => $value) {
            $meta = $this->buildFieldMeta($e, $field);
            $rules[$field] = $meta['validation'] ?? ['nullable'];
        }
        
        $e->fill($filtered);
        Validator::make($e->toArray(), $rules)->validate();
        $e = $this->updateOnlyExistanteAttribute($e->id, $filtered);

        return $e;
    }

    /**
     * Formatte les valeurs pour l’affichage inline
     */
    public function formatDisplayValues(RealisationCompetence $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'competence_id':
                    // Vue custom définie pour ce champ
                    $html = view('PkgApprentissage::realisationCompetence.custom.fields.competence', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'progression_cache':
                    // Vue custom définie pour ce champ
                    $html = view('PkgApprentissage::realisationCompetence.custom.fields.progression_cache', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'note_cache':
                    // Vue custom définie pour ce champ
                    $html = view('PkgApprentissage::realisationCompetence.custom.fields.note_cache', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;


                default:
                    // fallback générique si champ non pris en charge
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                    ])->render();

                    $out[$field] = ['html' => $html];
            }
        }
        return $out;
    }
}
