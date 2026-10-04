<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationProjet\Models\EquipeProjet;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe EquipeProjetService pour gérer la persistance de l'entité EquipeProjet.
 */
class BaseEquipeProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour equipeProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'description',
        'sys_color_id',
        'reference',
        'projet_id'
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
     * Constructeur de la classe EquipeProjetService.
     */
    public function __construct()
    {
        parent::__construct(new EquipeProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationProjet::equipeProjet.plural');
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
            $equipeProjet = $this->find($data['id']);
            $equipeProjet->fill($data);
        } else {
            $equipeProjet = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($equipeProjet->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $equipeProjet->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($equipeProjet->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($equipeProjet->id, $data);
            }
        }

        return $equipeProjet;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('equipeProjet');
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
            



    }


    /**
     * Crée une nouvelle instance de equipeProjet.
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
    public function getEquipeProjetStats(): array
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
            'table' => 'PkgCreationProjet::equipeProjet._table',
            default => 'PkgCreationProjet::equipeProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('equipeProjet_view_type', $default_view_type);
        $equipeProjet_viewType = $this->viewState->get('equipeProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('equipeProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.equipeProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.equipeProjet.visible");
        }
        
        // Récupération des données
        $equipeProjets_data = $this->paginate($params);
        $equipeProjets_stats = $this->getequipeProjetStats();
        $equipeProjets_total = $this->count();
        $equipeProjets_filters = $this->getFieldsFilterable();
        $equipeProjet_instance = $this->createInstance();
        $equipeProjet_viewTypes = $this->getViewTypes();
        $equipeProjet_partialViewName = $this->getPartialViewName($equipeProjet_viewType);
        $equipeProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.equipeProjet.stats', $equipeProjets_stats);
    
        $equipeProjets_permissions = [

            'edit-equipeProjet' => Auth::user()->can('edit-equipeProjet'),
            'destroy-equipeProjet' => Auth::user()->can('destroy-equipeProjet'),
            'show-equipeProjet' => Auth::user()->can('show-equipeProjet'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $equipeProjets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($equipeProjets_data as $item) {
                $equipeProjets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        $scopeVariables = $this->viewState->getScopeVariablesTitles('equipeProjet');

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'equipeProjet_viewTypes',
            'equipeProjet_viewType',
            'equipeProjets_data',
            'equipeProjets_stats',
            'equipeProjets_total',
            'equipeProjets_filters',
            'equipeProjet_instance',
            'equipeProjet_title',
            'contextKey',
            'equipeProjets_permissions',
            'equipeProjets_permissionsByItem',
            'scopeVariables'
        );
    
        return [
            'equipeProjets_data' => $equipeProjets_data,
            'equipeProjets_stats' => $equipeProjets_stats,
            'equipeProjets_total' => $equipeProjets_total,
            'equipeProjets_filters' => $equipeProjets_filters,
            'equipeProjet_instance' => $equipeProjet_instance,
            'equipeProjet_viewType' => $equipeProjet_viewType,
            'equipeProjet_viewTypes' => $equipeProjet_viewTypes,
            'equipeProjet_partialViewName' => $equipeProjet_partialViewName,
            'contextKey' => $contextKey,
            'equipeProjet_compact_value' => $compact_value,
            'equipeProjets_permissions' => $equipeProjets_permissions,
            'equipeProjets_permissionsByItem' => $equipeProjets_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $equipeProjet_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $equipeProjet_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($equipeProjet_ids as $id) {
            $equipeProjet = $this->find($id);
            $this->authorize('update', $equipeProjet);
    
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
            'sys_color_id',
            'projet_id'
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
    public function buildFieldMeta(EquipeProjet $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCreationProjet\App\Requests\EquipeProjetRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'equipe_projet',
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
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(EquipeProjet $e, array $changes): EquipeProjet
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
    public function formatDisplayValues(EquipeProjet $e, array $fields): array
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
                case 'sys_color_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'couleur',
                        'relationName' => 'sysColor'
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
