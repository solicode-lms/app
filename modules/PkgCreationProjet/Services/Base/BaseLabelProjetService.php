<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationProjet\Models\LabelProjet;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe LabelProjetService pour gérer la persistance de l'entité LabelProjet.
 */
class BaseLabelProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour labelProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'description',
        'projet_id',
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
     * Constructeur de la classe LabelProjetService.
     */
    public function __construct()
    {
        parent::__construct(new LabelProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationProjet::labelProjet.plural');
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
            $labelProjet = $this->find($data['id']);
            $labelProjet->fill($data);
        } else {
            $labelProjet = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($labelProjet->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $labelProjet->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($labelProjet->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($labelProjet->id, $data);
            }
        }

        return $labelProjet;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('labelProjet');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('projet_id', $scopeVariables)) {


                    $projetService = new \Modules\PkgCreationProjet\Services\ProjetService();
                    $projetIds = $this->getAvailableFilterValues('projet_id');
                    $projets = $projetService->getByIds($projetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationProjet::projet.plural"), 
                        'projet_id', 
                        \Modules\PkgCreationProjet\Models\Projet::class, 
                        'titre',
                        $projets
                    );
                }
            
            
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
     * Crée une nouvelle instance de labelProjet.
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
    public function getLabelProjetStats(): array
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
            'table' => 'PkgCreationProjet::labelProjet._table',
            default => 'PkgCreationProjet::labelProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('labelProjet_view_type', $default_view_type);
        $labelProjet_viewType = $this->viewState->get('labelProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('labelProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.labelProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.labelProjet.visible");
        }
        
        // Récupération des données
        $labelProjets_data = $this->paginate($params);
        $labelProjets_stats = $this->getlabelProjetStats();
        $labelProjets_total = $this->count();
        $labelProjets_filters = $this->getFieldsFilterable();
        $labelProjet_instance = $this->createInstance();
        $labelProjet_viewTypes = $this->getViewTypes();
        $labelProjet_partialViewName = $this->getPartialViewName($labelProjet_viewType);
        $labelProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.labelProjet.stats', $labelProjets_stats);
    
        $labelProjets_permissions = [

            'edit-labelProjet' => Auth::user()->can('edit-labelProjet'),
            'destroy-labelProjet' => Auth::user()->can('destroy-labelProjet'),
            'show-labelProjet' => Auth::user()->can('show-labelProjet'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $labelProjets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($labelProjets_data as $item) {
                $labelProjets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'labelProjet_viewTypes',
            'labelProjet_viewType',
            'labelProjets_data',
            'labelProjets_stats',
            'labelProjets_total',
            'labelProjets_filters',
            'labelProjet_instance',
            'labelProjet_title',
            'contextKey',
            'labelProjets_permissions',
            'labelProjets_permissionsByItem'
        );
    
        return [
            'labelProjets_data' => $labelProjets_data,
            'labelProjets_stats' => $labelProjets_stats,
            'labelProjets_total' => $labelProjets_total,
            'labelProjets_filters' => $labelProjets_filters,
            'labelProjet_instance' => $labelProjet_instance,
            'labelProjet_viewType' => $labelProjet_viewType,
            'labelProjet_viewTypes' => $labelProjet_viewTypes,
            'labelProjet_partialViewName' => $labelProjet_partialViewName,
            'contextKey' => $contextKey,
            'labelProjet_compact_value' => $compact_value,
            'labelProjets_permissions' => $labelProjets_permissions,
            'labelProjets_permissionsByItem' => $labelProjets_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $labelProjet_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $labelProjet_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($labelProjet_ids as $id) {
            $labelProjet = $this->find($id);
            $this->authorize('update', $labelProjet);
    
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
            'nom',
            'projet_id',
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
    public function buildFieldMeta(LabelProjet $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCreationProjet\App\Requests\LabelProjetRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'label_projet',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'nom':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'projet_id':
                 $values = (new \Modules\PkgCreationProjet\Services\ProjetService())
                    ->getAllForSelect($e->projet)
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
    public function applyInlinePatch(LabelProjet $e, array $changes): LabelProjet
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
    public function formatDisplayValues(LabelProjet $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'nom':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'projet_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'projet'
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
