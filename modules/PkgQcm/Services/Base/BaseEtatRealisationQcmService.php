<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\Models\EtatRealisationQcm;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe EtatRealisationQcmService pour gérer la persistance de l'entité EtatRealisationQcm.
 */
class BaseEtatRealisationQcmService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatRealisationQcms.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'reference',
        'titre',
        'description',
        'is_editable_by_formateur',
        'sys_color_id'
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
     * Constructeur de la classe EtatRealisationQcmService.
     */
    public function __construct()
    {
        parent::__construct(new EtatRealisationQcm());
        $this->fieldsFilterable = [];
        $this->title = __('PkgQcm::etatRealisationQcm.plural');
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
            $etatRealisationQcm = $this->find($data['id']);
            $etatRealisationQcm->fill($data);
        } else {
            $etatRealisationQcm = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($etatRealisationQcm->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $etatRealisationQcm->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($etatRealisationQcm->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($etatRealisationQcm->id, $data);
            }
        }

        return $etatRealisationQcm;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatRealisationQcm');
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
     * Crée une nouvelle instance de etatRealisationQcm.
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
    public function getEtatRealisationQcmStats(): array
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
            'table' => 'PkgQcm::etatRealisationQcm._table',
            default => 'PkgQcm::etatRealisationQcm._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatRealisationQcm_view_type', $default_view_type);
        $etatRealisationQcm_viewType = $this->viewState->get('etatRealisationQcm_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatRealisationQcm_view_type') === 'widgets') {
            $this->viewState->set("scope.etatRealisationQcm.visible", 1);
        }else{
            $this->viewState->remove("scope.etatRealisationQcm.visible");
        }
        
        // Récupération des données
        $etatRealisationQcms_data = $this->paginate($params);
        $etatRealisationQcms_stats = $this->getetatRealisationQcmStats();
        $etatRealisationQcms_total = $this->count();
        $etatRealisationQcms_filters = $this->getFieldsFilterable();
        $etatRealisationQcm_instance = $this->createInstance();
        $etatRealisationQcm_viewTypes = $this->getViewTypes();
        $etatRealisationQcm_partialViewName = $this->getPartialViewName($etatRealisationQcm_viewType);
        $etatRealisationQcm_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatRealisationQcm.stats', $etatRealisationQcms_stats);
    
        $etatRealisationQcms_permissions = [

            'edit-etatRealisationQcm' => Auth::user()->can('edit-etatRealisationQcm'),
            'destroy-etatRealisationQcm' => Auth::user()->can('destroy-etatRealisationQcm'),
            'show-etatRealisationQcm' => Auth::user()->can('show-etatRealisationQcm'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatRealisationQcms_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatRealisationQcms_data as $item) {
                $etatRealisationQcms_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatRealisationQcm_viewTypes',
            'etatRealisationQcm_viewType',
            'etatRealisationQcms_data',
            'etatRealisationQcms_stats',
            'etatRealisationQcms_total',
            'etatRealisationQcms_filters',
            'etatRealisationQcm_instance',
            'etatRealisationQcm_title',
            'contextKey',
            'etatRealisationQcms_permissions',
            'etatRealisationQcms_permissionsByItem'
        );
    
        return [
            'etatRealisationQcms_data' => $etatRealisationQcms_data,
            'etatRealisationQcms_stats' => $etatRealisationQcms_stats,
            'etatRealisationQcms_total' => $etatRealisationQcms_total,
            'etatRealisationQcms_filters' => $etatRealisationQcms_filters,
            'etatRealisationQcm_instance' => $etatRealisationQcm_instance,
            'etatRealisationQcm_viewType' => $etatRealisationQcm_viewType,
            'etatRealisationQcm_viewTypes' => $etatRealisationQcm_viewTypes,
            'etatRealisationQcm_partialViewName' => $etatRealisationQcm_partialViewName,
            'contextKey' => $contextKey,
            'etatRealisationQcm_compact_value' => $compact_value,
            'etatRealisationQcms_permissions' => $etatRealisationQcms_permissions,
            'etatRealisationQcms_permissionsByItem' => $etatRealisationQcms_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $etatRealisationQcm_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $etatRealisationQcm_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($etatRealisationQcm_ids as $id) {
            $etatRealisationQcm = $this->find($id);
            $this->authorize('update', $etatRealisationQcm);
    
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
            'sys_color_id'
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
    public function buildFieldMeta(EtatRealisationQcm $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgQcm\App\Requests\EtatRealisationQcmRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'etat_realisation_qcm',
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
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(EtatRealisationQcm $e, array $changes): EtatRealisationQcm
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
    public function formatDisplayValues(EtatRealisationQcm $e, array $fields): array
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
                case 'sys_color_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'couleur',
                        'relationName' => 'sysColor'
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
