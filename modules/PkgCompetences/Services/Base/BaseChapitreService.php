<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCompetences\Models\Chapitre;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe ChapitreService pour gérer la persistance de l'entité Chapitre.
 */
class BaseChapitreService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour chapitres.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'code',
        'nom',
        'unite_apprentissage_id',
        'duree_en_heure',
        'isOfficiel',
        'lien',
        'description',
        'formateur_id'
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
     * Constructeur de la classe ChapitreService.
     */
    public function __construct()
    {
        parent::__construct(new Chapitre());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::chapitre.plural');
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
            $chapitre = $this->find($data['id']);
            $chapitre->fill($data);
        } else {
            $chapitre = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($chapitre->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $chapitre->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($chapitre->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($chapitre->id, $data);
            }
        }

        return $chapitre;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('chapitre');
        $this->fieldsFilterable = [];
        
            
                $microCompetenceService = new \Modules\PkgCompetences\Services\MicroCompetenceService();
                $microCompetenceIds = $this->getAvailableFilterValues('UniteApprentissage.Micro_competence_id');
                $microCompetences = $microCompetenceService->getByIds($microCompetenceIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgCompetences::microCompetence.plural"),
                    'UniteApprentissage.Micro_competence_id', 
                    \Modules\PkgCompetences\Models\MicroCompetence::class,
                    "id", 
                    "id",
                    $microCompetences
                );
            
            
                if (!array_key_exists('isOfficiel', $scopeVariables)) {
                    $this->fieldsFilterable[] = [
                        'field' => 'isOfficiel', 
                        'type'  => 'Boolean', 
                        'label' => 'isOfficiel'
                    ];
                }
            
            
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
     * Crée une nouvelle instance de chapitre.
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
    public function getChapitreStats(): array
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
            'table' => 'PkgCompetences::chapitre._table',
            default => 'PkgCompetences::chapitre._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('chapitre_view_type', $default_view_type);
        $chapitre_viewType = $this->viewState->get('chapitre_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('chapitre_view_type') === 'widgets') {
            $this->viewState->set("scope.chapitre.visible", 1);
        }else{
            $this->viewState->remove("scope.chapitre.visible");
        }
        
        // Récupération des données
        $chapitres_data = $this->paginate($params);
        $chapitres_stats = $this->getchapitreStats();
        $chapitres_total = $this->count();
        $chapitres_filters = $this->getFieldsFilterable();
        $chapitre_instance = $this->createInstance();
        $chapitre_viewTypes = $this->getViewTypes();
        $chapitre_partialViewName = $this->getPartialViewName($chapitre_viewType);
        $chapitre_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.chapitre.stats', $chapitres_stats);
    
        $chapitres_permissions = [

            'edit-chapitre' => Auth::user()->can('edit-chapitre'),
            'destroy-chapitre' => Auth::user()->can('destroy-chapitre'),
            'show-chapitre' => Auth::user()->can('show-chapitre'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $chapitres_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($chapitres_data as $item) {
                $chapitres_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'chapitre_viewTypes',
            'chapitre_viewType',
            'chapitres_data',
            'chapitres_stats',
            'chapitres_total',
            'chapitres_filters',
            'chapitre_instance',
            'chapitre_title',
            'contextKey',
            'chapitres_permissions',
            'chapitres_permissionsByItem'
        );
    
        return [
            'chapitres_data' => $chapitres_data,
            'chapitres_stats' => $chapitres_stats,
            'chapitres_total' => $chapitres_total,
            'chapitres_filters' => $chapitres_filters,
            'chapitre_instance' => $chapitre_instance,
            'chapitre_viewType' => $chapitre_viewType,
            'chapitre_viewTypes' => $chapitre_viewTypes,
            'chapitre_partialViewName' => $chapitre_partialViewName,
            'contextKey' => $contextKey,
            'chapitre_compact_value' => $compact_value,
            'chapitres_permissions' => $chapitres_permissions,
            'chapitres_permissionsByItem' => $chapitres_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $chapitre_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $chapitre_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($chapitre_ids as $id) {
            $chapitre = $this->find($id);
            $this->authorize('update', $chapitre);
    
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
            'nom',
            'unite_apprentissage_id',
            'lien'
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
    public function buildFieldMeta(Chapitre $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCompetences\App\Requests\ChapitreRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'chapitre',
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
            case 'nom':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'unite_apprentissage_id':
                 $values = (new \Modules\PkgCompetences\Services\UniteApprentissageService())
                    ->getAllForSelect($e->uniteApprentissage)
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
            case 'lien':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(Chapitre $e, array $changes): Chapitre
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
    public function formatDisplayValues(Chapitre $e, array $fields): array
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
                case 'nom':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'unite_apprentissage_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'uniteApprentissage'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'lien':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'lien'
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
