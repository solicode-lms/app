<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgTableauBord\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgTableauBord\Models\LogActiviteFormateur;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe LogActiviteFormateurService pour gérer la persistance de l'entité LogActiviteFormateur.
 */
class BaseLogActiviteFormateurService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour logActiviteFormateurs.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'formateur_id',
        'type_activite',
        'duree_secondes',
        'date_activite',
        'semaine_annee',
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
     * Constructeur de la classe LogActiviteFormateurService.
     */
    public function __construct()
    {
        parent::__construct(new LogActiviteFormateur());
        $this->fieldsFilterable = [];
        $this->title = __('PkgTableauBord::logActiviteFormateur.plural');
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
            $logActiviteFormateur = $this->find($data['id']);
            $logActiviteFormateur->fill($data);
        } else {
            $logActiviteFormateur = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($logActiviteFormateur->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $logActiviteFormateur->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($logActiviteFormateur->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($logActiviteFormateur->id, $data);
            }
        }

        return $logActiviteFormateur;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('logActiviteFormateur');
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
     * Crée une nouvelle instance de logActiviteFormateur.
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
    public function getLogActiviteFormateurStats(): array
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
            'table' => 'PkgTableauBord::logActiviteFormateur._table',
            default => 'PkgTableauBord::logActiviteFormateur._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('logActiviteFormateur_view_type', $default_view_type);
        $logActiviteFormateur_viewType = $this->viewState->get('logActiviteFormateur_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('logActiviteFormateur_view_type') === 'widgets') {
            $this->viewState->set("scope.logActiviteFormateur.visible", 1);
        }else{
            $this->viewState->remove("scope.logActiviteFormateur.visible");
        }
        
        // Récupération des données
        $logActiviteFormateurs_data = $this->paginate($params);
        $logActiviteFormateurs_stats = $this->getlogActiviteFormateurStats();
        $logActiviteFormateurs_total = $this->count();
        $logActiviteFormateurs_filters = $this->getFieldsFilterable();
        $logActiviteFormateur_instance = $this->createInstance();
        $logActiviteFormateur_viewTypes = $this->getViewTypes();
        $logActiviteFormateur_partialViewName = $this->getPartialViewName($logActiviteFormateur_viewType);
        $logActiviteFormateur_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.logActiviteFormateur.stats', $logActiviteFormateurs_stats);
    
        $logActiviteFormateurs_permissions = [

            'edit-logActiviteFormateur' => Auth::user()->can('edit-logActiviteFormateur'),
            'destroy-logActiviteFormateur' => Auth::user()->can('destroy-logActiviteFormateur'),
            'show-logActiviteFormateur' => Auth::user()->can('show-logActiviteFormateur'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $logActiviteFormateurs_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($logActiviteFormateurs_data as $item) {
                $logActiviteFormateurs_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        $scopeVariables = $this->viewState->getScopeVariablesTitles('logActiviteFormateur');

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'logActiviteFormateur_viewTypes',
            'logActiviteFormateur_viewType',
            'logActiviteFormateurs_data',
            'logActiviteFormateurs_stats',
            'logActiviteFormateurs_total',
            'logActiviteFormateurs_filters',
            'logActiviteFormateur_instance',
            'logActiviteFormateur_title',
            'contextKey',
            'logActiviteFormateurs_permissions',
            'logActiviteFormateurs_permissionsByItem',
            'scopeVariables'
        );
    
        return [
            'logActiviteFormateurs_data' => $logActiviteFormateurs_data,
            'logActiviteFormateurs_stats' => $logActiviteFormateurs_stats,
            'logActiviteFormateurs_total' => $logActiviteFormateurs_total,
            'logActiviteFormateurs_filters' => $logActiviteFormateurs_filters,
            'logActiviteFormateur_instance' => $logActiviteFormateur_instance,
            'logActiviteFormateur_viewType' => $logActiviteFormateur_viewType,
            'logActiviteFormateur_viewTypes' => $logActiviteFormateur_viewTypes,
            'logActiviteFormateur_partialViewName' => $logActiviteFormateur_partialViewName,
            'contextKey' => $contextKey,
            'logActiviteFormateur_compact_value' => $compact_value,
            'logActiviteFormateurs_permissions' => $logActiviteFormateurs_permissions,
            'logActiviteFormateurs_permissionsByItem' => $logActiviteFormateurs_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $logActiviteFormateur_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $logActiviteFormateur_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($logActiviteFormateur_ids as $id) {
            $logActiviteFormateur = $this->find($id);
            $this->authorize('update', $logActiviteFormateur);
    
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
    public function buildFieldMeta(LogActiviteFormateur $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgTableauBord\App\Requests\LogActiviteFormateurRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'log_activite_formateur',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
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
    public function applyInlinePatch(LogActiviteFormateur $e, array $changes): LogActiviteFormateur
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
    public function formatDisplayValues(LogActiviteFormateur $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
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
