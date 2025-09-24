<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe EtatRealisationChapitreService pour gérer la persistance de l'entité EtatRealisationChapitre.
 */
class BaseEtatRealisationChapitreService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatRealisationChapitres.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'nom',
        'code',
        'sys_color_id',
        'is_editable_only_by_formateur',
        'description'
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
     * Constructeur de la classe EtatRealisationChapitreService.
     */
    public function __construct()
    {
        parent::__construct(new EtatRealisationChapitre());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::etatRealisationChapitre.plural');
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
            $etatRealisationChapitre = $this->find($data['id']);
            $etatRealisationChapitre->fill($data);
        } else {
            $etatRealisationChapitre = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($etatRealisationChapitre->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $etatRealisationChapitre->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($etatRealisationChapitre->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($etatRealisationChapitre->id, $data);
            }
        }

        return $etatRealisationChapitre;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatRealisationChapitre');
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
     * Crée une nouvelle instance de etatRealisationChapitre.
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
    public function getEtatRealisationChapitreStats(): array
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
            'table' => 'PkgApprentissage::etatRealisationChapitre._table',
            default => 'PkgApprentissage::etatRealisationChapitre._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatRealisationChapitre_view_type', $default_view_type);
        $etatRealisationChapitre_viewType = $this->viewState->get('etatRealisationChapitre_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatRealisationChapitre_view_type') === 'widgets') {
            $this->viewState->set("scope.etatRealisationChapitre.visible", 1);
        }else{
            $this->viewState->remove("scope.etatRealisationChapitre.visible");
        }
        
        // Récupération des données
        $etatRealisationChapitres_data = $this->paginate($params);
        $etatRealisationChapitres_stats = $this->getetatRealisationChapitreStats();
        $etatRealisationChapitres_total = $this->count();
        $etatRealisationChapitres_filters = $this->getFieldsFilterable();
        $etatRealisationChapitre_instance = $this->createInstance();
        $etatRealisationChapitre_viewTypes = $this->getViewTypes();
        $etatRealisationChapitre_partialViewName = $this->getPartialViewName($etatRealisationChapitre_viewType);
        $etatRealisationChapitre_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatRealisationChapitre.stats', $etatRealisationChapitres_stats);
    
        $etatRealisationChapitres_permissions = [

            'edit-etatRealisationChapitre' => Auth::user()->can('edit-etatRealisationChapitre'),
            'destroy-etatRealisationChapitre' => Auth::user()->can('destroy-etatRealisationChapitre'),
            'show-etatRealisationChapitre' => Auth::user()->can('show-etatRealisationChapitre'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatRealisationChapitres_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatRealisationChapitres_data as $item) {
                $etatRealisationChapitres_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatRealisationChapitre_viewTypes',
            'etatRealisationChapitre_viewType',
            'etatRealisationChapitres_data',
            'etatRealisationChapitres_stats',
            'etatRealisationChapitres_total',
            'etatRealisationChapitres_filters',
            'etatRealisationChapitre_instance',
            'etatRealisationChapitre_title',
            'contextKey',
            'etatRealisationChapitres_permissions',
            'etatRealisationChapitres_permissionsByItem'
        );
    
        return [
            'etatRealisationChapitres_data' => $etatRealisationChapitres_data,
            'etatRealisationChapitres_stats' => $etatRealisationChapitres_stats,
            'etatRealisationChapitres_total' => $etatRealisationChapitres_total,
            'etatRealisationChapitres_filters' => $etatRealisationChapitres_filters,
            'etatRealisationChapitre_instance' => $etatRealisationChapitre_instance,
            'etatRealisationChapitre_viewType' => $etatRealisationChapitre_viewType,
            'etatRealisationChapitre_viewTypes' => $etatRealisationChapitre_viewTypes,
            'etatRealisationChapitre_partialViewName' => $etatRealisationChapitre_partialViewName,
            'contextKey' => $contextKey,
            'etatRealisationChapitre_compact_value' => $compact_value,
            'etatRealisationChapitres_permissions' => $etatRealisationChapitres_permissions,
            'etatRealisationChapitres_permissionsByItem' => $etatRealisationChapitres_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $etatRealisationChapitre_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $etatRealisationChapitre_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($etatRealisationChapitre_ids as $id) {
            $etatRealisationChapitre = $this->find($id);
            $this->authorize('update', $etatRealisationChapitre);
    
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
    public function buildFieldMeta(EtatRealisationChapitre $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgApprentissage\App\Requests\EtatRealisationChapitreRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'etat_realisation_chapitre',
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
    public function applyInlinePatch(EtatRealisationChapitre $e, array $changes): EtatRealisationChapitre
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
    public function formatDisplayValues(EtatRealisationChapitre $e, array $fields): array
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
