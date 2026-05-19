<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgEvaluateurs\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgEvaluateurs\Models\EtatEvaluationProjet;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe EtatEvaluationProjetService pour gérer la persistance de l'entité EtatEvaluationProjet.
 */
class BaseEtatEvaluationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatEvaluationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'code',
        'titre',
        'description',
        'reference',
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
     * Constructeur de la classe EtatEvaluationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new EtatEvaluationProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgEvaluateurs::etatEvaluationProjet.plural');
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
            $etatEvaluationProjet = $this->find($data['id']);
            $etatEvaluationProjet->fill($data);
        } else {
            $etatEvaluationProjet = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($etatEvaluationProjet->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $etatEvaluationProjet->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($etatEvaluationProjet->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($etatEvaluationProjet->id, $data);
            }
        }

        return $etatEvaluationProjet;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatEvaluationProjet');
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
     * Crée une nouvelle instance de etatEvaluationProjet.
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
    public function getEtatEvaluationProjetStats(): array
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
            'table' => 'PkgEvaluateurs::etatEvaluationProjet._table',
            default => 'PkgEvaluateurs::etatEvaluationProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatEvaluationProjet_view_type', $default_view_type);
        $etatEvaluationProjet_viewType = $this->viewState->get('etatEvaluationProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatEvaluationProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.etatEvaluationProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.etatEvaluationProjet.visible");
        }
        
        // Récupération des données
        $etatEvaluationProjets_data = $this->paginate($params);
        $etatEvaluationProjets_stats = $this->getetatEvaluationProjetStats();
        $etatEvaluationProjets_total = $this->count();
        $etatEvaluationProjets_filters = $this->getFieldsFilterable();
        $etatEvaluationProjet_instance = $this->createInstance();
        $etatEvaluationProjet_viewTypes = $this->getViewTypes();
        $etatEvaluationProjet_partialViewName = $this->getPartialViewName($etatEvaluationProjet_viewType);
        $etatEvaluationProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatEvaluationProjet.stats', $etatEvaluationProjets_stats);
    
        $etatEvaluationProjets_permissions = [

            'edit-etatEvaluationProjet' => Auth::user()->can('edit-etatEvaluationProjet'),
            'destroy-etatEvaluationProjet' => Auth::user()->can('destroy-etatEvaluationProjet'),
            'show-etatEvaluationProjet' => Auth::user()->can('show-etatEvaluationProjet'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatEvaluationProjets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatEvaluationProjets_data as $item) {
                $etatEvaluationProjets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatEvaluationProjet_viewTypes',
            'etatEvaluationProjet_viewType',
            'etatEvaluationProjets_data',
            'etatEvaluationProjets_stats',
            'etatEvaluationProjets_total',
            'etatEvaluationProjets_filters',
            'etatEvaluationProjet_instance',
            'etatEvaluationProjet_title',
            'contextKey',
            'etatEvaluationProjets_permissions',
            'etatEvaluationProjets_permissionsByItem'
        );
    
        return [
            'etatEvaluationProjets_data' => $etatEvaluationProjets_data,
            'etatEvaluationProjets_stats' => $etatEvaluationProjets_stats,
            'etatEvaluationProjets_total' => $etatEvaluationProjets_total,
            'etatEvaluationProjets_filters' => $etatEvaluationProjets_filters,
            'etatEvaluationProjet_instance' => $etatEvaluationProjet_instance,
            'etatEvaluationProjet_viewType' => $etatEvaluationProjet_viewType,
            'etatEvaluationProjet_viewTypes' => $etatEvaluationProjet_viewTypes,
            'etatEvaluationProjet_partialViewName' => $etatEvaluationProjet_partialViewName,
            'contextKey' => $contextKey,
            'etatEvaluationProjet_compact_value' => $compact_value,
            'etatEvaluationProjets_permissions' => $etatEvaluationProjets_permissions,
            'etatEvaluationProjets_permissionsByItem' => $etatEvaluationProjets_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $etatEvaluationProjet_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $etatEvaluationProjet_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($etatEvaluationProjet_ids as $id) {
            $etatEvaluationProjet = $this->find($id);
            $this->authorize('update', $etatEvaluationProjet);
    
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
            'code',
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
    public function buildFieldMeta(EtatEvaluationProjet $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgEvaluateurs\App\Requests\EtatEvaluationProjetRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'etat_evaluation_projet',
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

            case 'code':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
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
    public function applyInlinePatch(EtatEvaluationProjet $e, array $changes): EtatEvaluationProjet
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
    public function formatDisplayValues(EtatEvaluationProjet $e, array $fields): array
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
                case 'code':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
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
