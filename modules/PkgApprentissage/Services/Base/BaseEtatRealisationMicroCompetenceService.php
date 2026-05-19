<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe EtatRealisationMicroCompetenceService pour gérer la persistance de l'entité EtatRealisationMicroCompetence.
 */
class BaseEtatRealisationMicroCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatRealisationMicroCompetences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'nom',
        'code',
        'sys_color_id',
        'is_editable_only_by_formateur',
        'description',
        'reference'
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
     * Constructeur de la classe EtatRealisationMicroCompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new EtatRealisationMicroCompetence());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::etatRealisationMicroCompetence.plural');
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
            $etatRealisationMicroCompetence = $this->find($data['id']);
            $etatRealisationMicroCompetence->fill($data);
        } else {
            $etatRealisationMicroCompetence = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($etatRealisationMicroCompetence->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $etatRealisationMicroCompetence->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($etatRealisationMicroCompetence->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($etatRealisationMicroCompetence->id, $data);
            }
        }

        return $etatRealisationMicroCompetence;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatRealisationMicroCompetence');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('sys_color_id', $scopeVariables)) {


                    $sysColorService = new \Modules\Core\Services\SysColorService();
                    $sysColorIds = $this->getAvailableFilterValues('sys_color_id');
                    $sysColors = $sysColorService->getByIds($sysColorIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("Core::sysColor.plural"), 
                        'sys_color_id', 
                        \Modules\Core\Models\SysColor::class, 
                        'name',
                        $sysColors
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de etatRealisationMicroCompetence.
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
    public function getEtatRealisationMicroCompetenceStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
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
            'table' => 'PkgApprentissage::etatRealisationMicroCompetence._table',
            default => 'PkgApprentissage::etatRealisationMicroCompetence._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatRealisationMicroCompetence_view_type', $default_view_type);
        $etatRealisationMicroCompetence_viewType = $this->viewState->get('etatRealisationMicroCompetence_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatRealisationMicroCompetence_view_type') === 'widgets') {
            $this->viewState->set("scope.etatRealisationMicroCompetence.visible", 1);
        }else{
            $this->viewState->remove("scope.etatRealisationMicroCompetence.visible");
        }
        
        // Récupération des données
        $etatRealisationMicroCompetences_data = $this->paginate($params);
        $etatRealisationMicroCompetences_stats = $this->getetatRealisationMicroCompetenceStats();
        $etatRealisationMicroCompetences_total = $this->count();
        $etatRealisationMicroCompetences_filters = $this->getFieldsFilterable();
        $etatRealisationMicroCompetence_instance = $this->createInstance();
        $etatRealisationMicroCompetence_viewTypes = $this->getViewTypes();
        $etatRealisationMicroCompetence_partialViewName = $this->getPartialViewName($etatRealisationMicroCompetence_viewType);
        $etatRealisationMicroCompetence_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatRealisationMicroCompetence.stats', $etatRealisationMicroCompetences_stats);
    
        $etatRealisationMicroCompetences_permissions = [

            'edit-etatRealisationMicroCompetence' => Auth::user()->can('edit-etatRealisationMicroCompetence'),
            'destroy-etatRealisationMicroCompetence' => Auth::user()->can('destroy-etatRealisationMicroCompetence'),
            'show-etatRealisationMicroCompetence' => Auth::user()->can('show-etatRealisationMicroCompetence'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatRealisationMicroCompetences_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatRealisationMicroCompetences_data as $item) {
                $etatRealisationMicroCompetences_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatRealisationMicroCompetence_viewTypes',
            'etatRealisationMicroCompetence_viewType',
            'etatRealisationMicroCompetences_data',
            'etatRealisationMicroCompetences_stats',
            'etatRealisationMicroCompetences_total',
            'etatRealisationMicroCompetences_filters',
            'etatRealisationMicroCompetence_instance',
            'etatRealisationMicroCompetence_title',
            'contextKey',
            'etatRealisationMicroCompetences_permissions',
            'etatRealisationMicroCompetences_permissionsByItem'
        );
    
        return [
            'etatRealisationMicroCompetences_data' => $etatRealisationMicroCompetences_data,
            'etatRealisationMicroCompetences_stats' => $etatRealisationMicroCompetences_stats,
            'etatRealisationMicroCompetences_total' => $etatRealisationMicroCompetences_total,
            'etatRealisationMicroCompetences_filters' => $etatRealisationMicroCompetences_filters,
            'etatRealisationMicroCompetence_instance' => $etatRealisationMicroCompetence_instance,
            'etatRealisationMicroCompetence_viewType' => $etatRealisationMicroCompetence_viewType,
            'etatRealisationMicroCompetence_viewTypes' => $etatRealisationMicroCompetence_viewTypes,
            'etatRealisationMicroCompetence_partialViewName' => $etatRealisationMicroCompetence_partialViewName,
            'contextKey' => $contextKey,
            'etatRealisationMicroCompetence_compact_value' => $compact_value,
            'etatRealisationMicroCompetences_permissions' => $etatRealisationMicroCompetences_permissions,
            'etatRealisationMicroCompetences_permissionsByItem' => $etatRealisationMicroCompetences_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $etatRealisationMicroCompetence_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $etatRealisationMicroCompetence_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($etatRealisationMicroCompetence_ids as $id) {
            $etatRealisationMicroCompetence = $this->find($id);
            $this->authorize('update', $etatRealisationMicroCompetence);
    
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
            'ordre',
            'nom',
            'code',
            'sys_color_id',
            'is_editable_only_by_formateur'
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
    public function buildFieldMeta(EtatRealisationMicroCompetence $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgApprentissage\App\Requests\EtatRealisationMicroCompetenceRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'etat_realisation_micro_competence',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'ordre':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            case 'nom':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'code':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'sys_color_id':
                 $values = (new \Modules\Core\Services\SysColorService())
                    ->getAllForSelect($e->sysColor)
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
            case 'is_editable_only_by_formateur':
                return $this->computeFieldMeta($e, $field, $meta, 'boolean');

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(EtatRealisationMicroCompetence $e, array $changes): EtatRealisationMicroCompetence
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
    public function formatDisplayValues(EtatRealisationMicroCompetence $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'ordre':
                    $html = view('Core::fields_by_type.integer', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'ordre'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'nom':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'code':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'sys_color_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'couleur',
                        'relationName' => 'sysColor'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'is_editable_only_by_formateur':
                    $html = view('Core::fields_by_type.boolean', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
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
