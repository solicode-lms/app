<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationProjet\Models\Livrable;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe LivrableService pour gérer la persistance de l'entité Livrable.
 */
class BaseLivrableService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour livrables.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nature_livrable_id',
        'titre',
        'projet_id',
        'description',
        'reference',
        'is_affichable_seulement_par_formateur'
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
     * Constructeur de la classe LivrableService.
     */
    public function __construct()
    {
        parent::__construct(new Livrable());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationProjet::livrable.plural');
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
            $livrable = $this->find($data['id']);
            $livrable->fill($data);
        } else {
            $livrable = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($livrable->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $livrable->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($livrable->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($livrable->id, $data);
            }
        }

        return $livrable;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('livrable');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('nature_livrable_id', $scopeVariables)) {


                    $natureLivrableService = new \Modules\PkgCreationProjet\Services\NatureLivrableService();
                    $natureLivrableIds = $this->getAvailableFilterValues('nature_livrable_id');
                    $natureLivrables = $natureLivrableService->getByIds($natureLivrableIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationProjet::natureLivrable.plural"), 
                        'nature_livrable_id', 
                        \Modules\PkgCreationProjet\Models\NatureLivrable::class, 
                        'nom',
                        $natureLivrables
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
     * Crée une nouvelle instance de livrable.
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
    public function getLivrableStats(): array
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
            'table' => 'PkgCreationProjet::livrable._table',
            default => 'PkgCreationProjet::livrable._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('livrable_view_type', $default_view_type);
        $livrable_viewType = $this->viewState->get('livrable_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('livrable_view_type') === 'widgets') {
            $this->viewState->set("scope.livrable.visible", 1);
        }else{
            $this->viewState->remove("scope.livrable.visible");
        }
        
        // Récupération des données
        $livrables_data = $this->paginate($params);
        $livrables_stats = $this->getlivrableStats();
        $livrables_total = $this->count();
        $livrables_filters = $this->getFieldsFilterable();
        $livrable_instance = $this->createInstance();
        $livrable_viewTypes = $this->getViewTypes();
        $livrable_partialViewName = $this->getPartialViewName($livrable_viewType);
        $livrable_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.livrable.stats', $livrables_stats);
    
        $livrables_permissions = [

            'edit-livrable' => Auth::user()->can('edit-livrable'),
            'destroy-livrable' => Auth::user()->can('destroy-livrable'),
            'show-livrable' => Auth::user()->can('show-livrable'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $livrables_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($livrables_data as $item) {
                $livrables_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'livrable_viewTypes',
            'livrable_viewType',
            'livrables_data',
            'livrables_stats',
            'livrables_total',
            'livrables_filters',
            'livrable_instance',
            'livrable_title',
            'contextKey',
            'livrables_permissions',
            'livrables_permissionsByItem'
        );
    
        return [
            'livrables_data' => $livrables_data,
            'livrables_stats' => $livrables_stats,
            'livrables_total' => $livrables_total,
            'livrables_filters' => $livrables_filters,
            'livrable_instance' => $livrable_instance,
            'livrable_viewType' => $livrable_viewType,
            'livrable_viewTypes' => $livrable_viewTypes,
            'livrable_partialViewName' => $livrable_partialViewName,
            'contextKey' => $contextKey,
            'livrable_compact_value' => $compact_value,
            'livrables_permissions' => $livrables_permissions,
            'livrables_permissionsByItem' => $livrables_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $livrable_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $livrable_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($livrable_ids as $id) {
            $livrable = $this->find($id);
            $this->authorize('update', $livrable);
    
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
            'nature_livrable_id',
            'titre'
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
    public function buildFieldMeta(Livrable $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCreationProjet\App\Requests\LivrableRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'livrable',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'nature_livrable_id':
                 $values = (new \Modules\PkgCreationProjet\Services\NatureLivrableService())
                    ->getAllForSelect($e->natureLivrable)
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
            case 'titre':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(Livrable $e, array $changes): Livrable
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
    public function formatDisplayValues(Livrable $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'nature_livrable_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'natureLivrable'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'titre':
                    $html = view('Core::fields_by_type.string', [
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
