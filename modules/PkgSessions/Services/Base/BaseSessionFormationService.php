<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgSessions\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgSessions\Models\SessionFormation;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe SessionFormationService pour gérer la persistance de l'entité SessionFormation.
 */
class BaseSessionFormationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour sessionFormations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'titre',
        'code',
        'thematique',
        'filiere_id',
        'objectifs_pedagogique',
        'titre_prototype',
        'description_prototype',
        'contraintes_prototype',
        'titre_projet',
        'description_projet',
        'contraintes_projet',
        'remarques',
        'date_debut',
        'date_fin',
        'jour_feries_vacances',
        'reference',
        'annee_formation_id'
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
     * Constructeur de la classe SessionFormationService.
     */
    public function __construct()
    {
        parent::__construct(new SessionFormation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgSessions::sessionFormation.plural');
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
            $sessionFormation = $this->find($data['id']);
            $sessionFormation->fill($data);
        } else {
            $sessionFormation = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($sessionFormation->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $sessionFormation->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($sessionFormation->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($sessionFormation->id, $data);
            }
        }

        return $sessionFormation;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('sessionFormation');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('filiere_id', $scopeVariables)) {


                    $filiereService = new \Modules\PkgFormation\Services\FiliereService();
                    $filiereIds = $this->getAvailableFilterValues('filiere_id');
                    $filieres = $filiereService->getByIds($filiereIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgFormation::filiere.plural"), 
                        'filiere_id', 
                        \Modules\PkgFormation\Models\Filiere::class, 
                        'code',
                        $filieres
                    );
                }
            
            
                if (!array_key_exists('annee_formation_id', $scopeVariables)) {


                    $anneeFormationService = new \Modules\PkgFormation\Services\AnneeFormationService();
                    $anneeFormationIds = $this->getAvailableFilterValues('annee_formation_id');
                    $anneeFormations = $anneeFormationService->getByIds($anneeFormationIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgFormation::anneeFormation.plural"), 
                        'annee_formation_id', 
                        \Modules\PkgFormation\Models\AnneeFormation::class, 
                        'titre',
                        $anneeFormations
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de sessionFormation.
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
    public function getSessionFormationStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }


    public function add_projet(int $sessionFormationId)
    {
        $sessionFormation = $this->find($sessionFormationId);
        if (!$sessionFormation) {
            return false; 
        }
        $value =  $sessionFormation->save();
        $this->pushServiceMessage("info","Traitement title", "message : résultat de traitement");
        return $value;
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
            'table' => 'PkgSessions::sessionFormation._table',
            default => 'PkgSessions::sessionFormation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('sessionFormation_view_type', $default_view_type);
        $sessionFormation_viewType = $this->viewState->get('sessionFormation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('sessionFormation_view_type') === 'widgets') {
            $this->viewState->set("scope.sessionFormation.visible", 1);
        }else{
            $this->viewState->remove("scope.sessionFormation.visible");
        }
        
        // Récupération des données
        $sessionFormations_data = $this->paginate($params);
        $sessionFormations_stats = $this->getsessionFormationStats();
        $sessionFormations_total = $this->count();
        $sessionFormations_filters = $this->getFieldsFilterable();
        $sessionFormation_instance = $this->createInstance();
        $sessionFormation_viewTypes = $this->getViewTypes();
        $sessionFormation_partialViewName = $this->getPartialViewName($sessionFormation_viewType);
        $sessionFormation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.sessionFormation.stats', $sessionFormations_stats);
    
        $sessionFormations_permissions = [
            'add_projet-sessionFormation' => Auth::user()->can('add_projet-sessionFormation'),           
            
            'edit-sessionFormation' => Auth::user()->can('edit-sessionFormation'),
            'destroy-sessionFormation' => Auth::user()->can('destroy-sessionFormation'),
            'show-sessionFormation' => Auth::user()->can('show-sessionFormation'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $sessionFormations_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($sessionFormations_data as $item) {
                $sessionFormations_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'sessionFormation_viewTypes',
            'sessionFormation_viewType',
            'sessionFormations_data',
            'sessionFormations_stats',
            'sessionFormations_total',
            'sessionFormations_filters',
            'sessionFormation_instance',
            'sessionFormation_title',
            'contextKey',
            'sessionFormations_permissions',
            'sessionFormations_permissionsByItem'
        );
    
        return [
            'sessionFormations_data' => $sessionFormations_data,
            'sessionFormations_stats' => $sessionFormations_stats,
            'sessionFormations_total' => $sessionFormations_total,
            'sessionFormations_filters' => $sessionFormations_filters,
            'sessionFormation_instance' => $sessionFormation_instance,
            'sessionFormation_viewType' => $sessionFormation_viewType,
            'sessionFormation_viewTypes' => $sessionFormation_viewTypes,
            'sessionFormation_partialViewName' => $sessionFormation_partialViewName,
            'contextKey' => $contextKey,
            'sessionFormation_compact_value' => $compact_value,
            'sessionFormations_permissions' => $sessionFormations_permissions,
            'sessionFormations_permissionsByItem' => $sessionFormations_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $sessionFormation_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $sessionFormation_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($sessionFormation_ids as $id) {
            $sessionFormation = $this->find($id);
            $this->authorize('update', $sessionFormation);
    
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
            'objectifs_pedagogique',
            'AlignementUa'
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
    public function buildFieldMeta(SessionFormation $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgSessions\App\Requests\SessionFormationRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'session_formation',
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
            case 'objectifs_pedagogique':
                return $this->computeFieldMeta($e, $field, $meta, 'text');

            case 'AlignementUa':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(SessionFormation $e, array $changes): SessionFormation
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
    public function formatDisplayValues(SessionFormation $e, array $fields): array
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
                    // Vue custom définie pour ce champ
                    $html = view('PkgSessions::sessionFormation.custom.fields.titre', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'objectifs_pedagogique':
                    // Vue custom définie pour ce champ
                    $html = view('PkgSessions::sessionFormation.custom.fields.objectifs_pedagogique', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'AlignementUa':
                    // Vue custom définie pour ce champ
                    $html = view('PkgSessions::sessionFormation.custom.fields.alignementUas', [
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
