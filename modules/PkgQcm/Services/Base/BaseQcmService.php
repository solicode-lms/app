<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\Models\Qcm;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe QcmService pour gérer la persistance de l'entité Qcm.
 */
class BaseQcmService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour qcms.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'reference',
        'titre',
        'description',
        'duree_minutes',
        'is_duree_limitee',
        'is_publie',
        'formateur_id'
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
     * Constructeur de la classe QcmService.
     */
    public function __construct()
    {
        parent::__construct(new Qcm());
        $this->fieldsFilterable = [];
        $this->title = __('PkgQcm::qcm.plural');
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
            $qcm = $this->find($data['id']);
            $qcm->fill($data);
        } else {
            $qcm = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($qcm->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $qcm->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($qcm->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($qcm->id, $data);
            }
        }

        return $qcm;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('qcm');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('formateur_id', $scopeVariables)) {


                    $formateurService = new \Modules\PkgFormation\Services\FormateurService();
                    $formateurIds = $this->getAvailableFilterValues('formateur_id');
                    $formateurs = $formateurService->getByIds($formateurIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgFormation::formateur.plural"), 
                        'formateur_id', 
                        \Modules\PkgFormation\Models\Formateur::class, 
                        'nom',
                        $formateurs
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de qcm.
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
    public function getQcmStats(): array
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
            'table' => 'PkgQcm::qcm._table',
            default => 'PkgQcm::qcm._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('qcm_view_type', $default_view_type);
        $qcm_viewType = $this->viewState->get('qcm_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('qcm_view_type') === 'widgets') {
            $this->viewState->set("scope.qcm.visible", 1);
        }else{
            $this->viewState->remove("scope.qcm.visible");
        }
        
        // Récupération des données
        $qcms_data = $this->paginate($params);
        $qcms_stats = $this->getqcmStats();
        $qcms_total = $this->count();
        $qcms_filters = $this->getFieldsFilterable();
        $qcm_instance = $this->createInstance();
        $qcm_viewTypes = $this->getViewTypes();
        $qcm_partialViewName = $this->getPartialViewName($qcm_viewType);
        $qcm_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.qcm.stats', $qcms_stats);
    
        $qcms_permissions = [

            'edit-qcm' => Auth::user()->can('edit-qcm'),
            'destroy-qcm' => Auth::user()->can('destroy-qcm'),
            'show-qcm' => Auth::user()->can('show-qcm'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $qcms_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($qcms_data as $item) {
                $qcms_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'qcm_viewTypes',
            'qcm_viewType',
            'qcms_data',
            'qcms_stats',
            'qcms_total',
            'qcms_filters',
            'qcm_instance',
            'qcm_title',
            'contextKey',
            'qcms_permissions',
            'qcms_permissionsByItem'
        );
    
        return [
            'qcms_data' => $qcms_data,
            'qcms_stats' => $qcms_stats,
            'qcms_total' => $qcms_total,
            'qcms_filters' => $qcms_filters,
            'qcm_instance' => $qcm_instance,
            'qcm_viewType' => $qcm_viewType,
            'qcm_viewTypes' => $qcm_viewTypes,
            'qcm_partialViewName' => $qcm_partialViewName,
            'contextKey' => $contextKey,
            'qcm_compact_value' => $compact_value,
            'qcms_permissions' => $qcms_permissions,
            'qcms_permissionsByItem' => $qcms_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $qcm_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $qcm_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($qcm_ids as $id) {
            $qcm = $this->find($id);
            $this->authorize('update', $qcm);
    
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
            'titre',
            'formateur_id'
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
    public function buildFieldMeta(Qcm $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgQcm\App\Requests\QcmRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'qcm',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'titre':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'formateur_id':
                 $values = (new \Modules\PkgFormation\Services\FormateurService())
                    ->getAllForSelect($e->formateur)
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
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(Qcm $e, array $changes): Qcm
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
    public function formatDisplayValues(Qcm $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'titre':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'formateur_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'formateur'
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
