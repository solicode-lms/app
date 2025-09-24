<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgSessions\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgSessions\Models\LivrableSession;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe LivrableSessionService pour gérer la persistance de l'entité LivrableSession.
 */
class BaseLivrableSessionService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour livrableSessions.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'titre',
        'description',
        'session_formation_id',
        'nature_livrable_id'
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
     * Constructeur de la classe LivrableSessionService.
     */
    public function __construct()
    {
        parent::__construct(new LivrableSession());
        $this->fieldsFilterable = [];
        $this->title = __('PkgSessions::livrableSession.plural');
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
            $livrableSession = $this->find($data['id']);
            $livrableSession->fill($data);
        } else {
            $livrableSession = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($livrableSession->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $livrableSession->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($livrableSession->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($livrableSession->id, $data);
            }
        }

        return $livrableSession;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('livrableSession');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('session_formation_id', $scopeVariables)) {


                    $sessionFormationService = new \Modules\PkgSessions\Services\SessionFormationService();
                    $sessionFormationIds = $this->getAvailableFilterValues('session_formation_id');
                    $sessionFormations = $sessionFormationService->getByIds($sessionFormationIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgSessions::sessionFormation.plural"), 
                        'session_formation_id', 
                        \Modules\PkgSessions\Models\SessionFormation::class, 
                        'code',
                        $sessionFormations
                    );
                }
            
            
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
            



    }


    /**
     * Crée une nouvelle instance de livrableSession.
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
    public function getLivrableSessionStats(): array
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
            'table' => 'PkgSessions::livrableSession._table',
            default => 'PkgSessions::livrableSession._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('livrableSession_view_type', $default_view_type);
        $livrableSession_viewType = $this->viewState->get('livrableSession_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('livrableSession_view_type') === 'widgets') {
            $this->viewState->set("scope.livrableSession.visible", 1);
        }else{
            $this->viewState->remove("scope.livrableSession.visible");
        }
        
        // Récupération des données
        $livrableSessions_data = $this->paginate($params);
        $livrableSessions_stats = $this->getlivrableSessionStats();
        $livrableSessions_total = $this->count();
        $livrableSessions_filters = $this->getFieldsFilterable();
        $livrableSession_instance = $this->createInstance();
        $livrableSession_viewTypes = $this->getViewTypes();
        $livrableSession_partialViewName = $this->getPartialViewName($livrableSession_viewType);
        $livrableSession_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.livrableSession.stats', $livrableSessions_stats);
    
        $livrableSessions_permissions = [

            'edit-livrableSession' => Auth::user()->can('edit-livrableSession'),
            'destroy-livrableSession' => Auth::user()->can('destroy-livrableSession'),
            'show-livrableSession' => Auth::user()->can('show-livrableSession'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $livrableSessions_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($livrableSessions_data as $item) {
                $livrableSessions_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'livrableSession_viewTypes',
            'livrableSession_viewType',
            'livrableSessions_data',
            'livrableSessions_stats',
            'livrableSessions_total',
            'livrableSessions_filters',
            'livrableSession_instance',
            'livrableSession_title',
            'contextKey',
            'livrableSessions_permissions',
            'livrableSessions_permissionsByItem'
        );
    
        return [
            'livrableSessions_data' => $livrableSessions_data,
            'livrableSessions_stats' => $livrableSessions_stats,
            'livrableSessions_total' => $livrableSessions_total,
            'livrableSessions_filters' => $livrableSessions_filters,
            'livrableSession_instance' => $livrableSession_instance,
            'livrableSession_viewType' => $livrableSession_viewType,
            'livrableSession_viewTypes' => $livrableSession_viewTypes,
            'livrableSession_partialViewName' => $livrableSession_partialViewName,
            'contextKey' => $contextKey,
            'livrableSession_compact_value' => $compact_value,
            'livrableSessions_permissions' => $livrableSessions_permissions,
            'livrableSessions_permissionsByItem' => $livrableSessions_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $livrableSession_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $livrableSession_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($livrableSession_ids as $id) {
            $livrableSession = $this->find($id);
            $this->authorize('update', $livrableSession);
    
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
            'titre',
            'session_formation_id',
            'nature_livrable_id'
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
    public function buildFieldMeta(LivrableSession $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgSessions\App\Requests\LivrableSessionRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'livrable_session',
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

            case 'titre':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'session_formation_id':
                 $values = (new \Modules\PkgSessions\Services\SessionFormationService())
                    ->getAllForSelect($e->sessionFormation)
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
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(LivrableSession $e, array $changes): LivrableSession
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
    public function formatDisplayValues(LivrableSession $e, array $fields): array
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
                case 'titre':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'session_formation_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'sessionFormation'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'nature_livrable_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'natureLivrable'
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
