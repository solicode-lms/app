<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCompetences\Models\PhaseEvaluation;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe PhaseEvaluationService pour gérer la persistance de l'entité PhaseEvaluation.
 */
class BasePhaseEvaluationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour phaseEvaluations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'reference',
        'code',
        'libelle',
        'coefficient',
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
     * Constructeur de la classe PhaseEvaluationService.
     */
    public function __construct()
    {
        parent::__construct(new PhaseEvaluation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::phaseEvaluation.plural');
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
            $phaseEvaluation = $this->find($data['id']);
            $phaseEvaluation->fill($data);
        } else {
            $phaseEvaluation = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($phaseEvaluation->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $phaseEvaluation->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($phaseEvaluation->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($phaseEvaluation->id, $data);
            }
        }

        return $phaseEvaluation;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('phaseEvaluation');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de phaseEvaluation.
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
    public function getPhaseEvaluationStats(): array
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
            'table' => 'PkgCompetences::phaseEvaluation._table',
            default => 'PkgCompetences::phaseEvaluation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('phaseEvaluation_view_type', $default_view_type);
        $phaseEvaluation_viewType = $this->viewState->get('phaseEvaluation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('phaseEvaluation_view_type') === 'widgets') {
            $this->viewState->set("scope.phaseEvaluation.visible", 1);
        }else{
            $this->viewState->remove("scope.phaseEvaluation.visible");
        }
        
        // Récupération des données
        $phaseEvaluations_data = $this->paginate($params);
        $phaseEvaluations_stats = $this->getphaseEvaluationStats();
        $phaseEvaluations_total = $this->count();
        $phaseEvaluations_filters = $this->getFieldsFilterable();
        $phaseEvaluation_instance = $this->createInstance();
        $phaseEvaluation_viewTypes = $this->getViewTypes();
        $phaseEvaluation_partialViewName = $this->getPartialViewName($phaseEvaluation_viewType);
        $phaseEvaluation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.phaseEvaluation.stats', $phaseEvaluations_stats);
    
        $phaseEvaluations_permissions = [

            'edit-phaseEvaluation' => Auth::user()->can('edit-phaseEvaluation'),
            'destroy-phaseEvaluation' => Auth::user()->can('destroy-phaseEvaluation'),
            'show-phaseEvaluation' => Auth::user()->can('show-phaseEvaluation'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $phaseEvaluations_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($phaseEvaluations_data as $item) {
                $phaseEvaluations_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'phaseEvaluation_viewTypes',
            'phaseEvaluation_viewType',
            'phaseEvaluations_data',
            'phaseEvaluations_stats',
            'phaseEvaluations_total',
            'phaseEvaluations_filters',
            'phaseEvaluation_instance',
            'phaseEvaluation_title',
            'contextKey',
            'phaseEvaluations_permissions',
            'phaseEvaluations_permissionsByItem'
        );
    
        return [
            'phaseEvaluations_data' => $phaseEvaluations_data,
            'phaseEvaluations_stats' => $phaseEvaluations_stats,
            'phaseEvaluations_total' => $phaseEvaluations_total,
            'phaseEvaluations_filters' => $phaseEvaluations_filters,
            'phaseEvaluation_instance' => $phaseEvaluation_instance,
            'phaseEvaluation_viewType' => $phaseEvaluation_viewType,
            'phaseEvaluation_viewTypes' => $phaseEvaluation_viewTypes,
            'phaseEvaluation_partialViewName' => $phaseEvaluation_partialViewName,
            'contextKey' => $contextKey,
            'phaseEvaluation_compact_value' => $compact_value,
            'phaseEvaluations_permissions' => $phaseEvaluations_permissions,
            'phaseEvaluations_permissionsByItem' => $phaseEvaluations_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $phaseEvaluation_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $phaseEvaluation_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($phaseEvaluation_ids as $id) {
            $phaseEvaluation = $this->find($id);
            $this->authorize('update', $phaseEvaluation);
    
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
            'libelle'
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
    public function buildFieldMeta(PhaseEvaluation $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCompetences\App\Requests\PhaseEvaluationRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'phase_evaluation',
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
            case 'libelle':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(PhaseEvaluation $e, array $changes): PhaseEvaluation
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
    public function formatDisplayValues(PhaseEvaluation $e, array $fields): array
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
                case 'libelle':
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
