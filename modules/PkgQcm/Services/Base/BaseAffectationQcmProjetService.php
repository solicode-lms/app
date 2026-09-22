<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\Models\AffectationQcmProjet;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe AffectationQcmProjetService pour gérer la persistance de l'entité AffectationQcmProjet.
 */
class BaseAffectationQcmProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour affectationQcmProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'reference',
        'affectation_projet_id',
        'qcm_id'
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
     * Constructeur de la classe AffectationQcmProjetService.
     */
    public function __construct()
    {
        parent::__construct(new AffectationQcmProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgQcm::affectationQcmProjet.plural');
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
            $affectationQcmProjet = $this->find($data['id']);
            $affectationQcmProjet->fill($data);
        } else {
            $affectationQcmProjet = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($affectationQcmProjet->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $affectationQcmProjet->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($affectationQcmProjet->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($affectationQcmProjet->id, $data);
            }
        }

        return $affectationQcmProjet;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('affectationQcmProjet');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('affectation_projet_id', $scopeVariables)) {


                    $affectationProjetService = new \Modules\PkgRealisationProjets\Services\AffectationProjetService();
                    $affectationProjetIds = $this->getAvailableFilterValues('affectation_projet_id');
                    $affectationProjets = $affectationProjetService->getByIds($affectationProjetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationProjets::affectationProjet.plural"), 
                        'affectation_projet_id', 
                        \Modules\PkgRealisationProjets\Models\AffectationProjet::class, 
                        'id',
                        $affectationProjets
                    );
                }
            
            
                if (!array_key_exists('qcm_id', $scopeVariables)) {


                    $qcmService = new \Modules\PkgQcm\Services\QcmService();
                    $qcmIds = $this->getAvailableFilterValues('qcm_id');
                    $qcms = $qcmService->getByIds($qcmIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgQcm::qcm.plural"), 
                        'qcm_id', 
                        \Modules\PkgQcm\Models\Qcm::class, 
                        'titre',
                        $qcms
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de affectationQcmProjet.
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
    public function getAffectationQcmProjetStats(): array
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
            'table' => 'PkgQcm::affectationQcmProjet._table',
            default => 'PkgQcm::affectationQcmProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('affectationQcmProjet_view_type', $default_view_type);
        $affectationQcmProjet_viewType = $this->viewState->get('affectationQcmProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('affectationQcmProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.affectationQcmProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.affectationQcmProjet.visible");
        }
        
        // Récupération des données
        $affectationQcmProjets_data = $this->paginate($params);
        $affectationQcmProjets_stats = $this->getaffectationQcmProjetStats();
        $affectationQcmProjets_total = $this->count();
        $affectationQcmProjets_filters = $this->getFieldsFilterable();
        $affectationQcmProjet_instance = $this->createInstance();
        $affectationQcmProjet_viewTypes = $this->getViewTypes();
        $affectationQcmProjet_partialViewName = $this->getPartialViewName($affectationQcmProjet_viewType);
        $affectationQcmProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.affectationQcmProjet.stats', $affectationQcmProjets_stats);
    
        $affectationQcmProjets_permissions = [

            'edit-affectationQcmProjet' => Auth::user()->can('edit-affectationQcmProjet'),
            'destroy-affectationQcmProjet' => Auth::user()->can('destroy-affectationQcmProjet'),
            'show-affectationQcmProjet' => Auth::user()->can('show-affectationQcmProjet'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $affectationQcmProjets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($affectationQcmProjets_data as $item) {
                $affectationQcmProjets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'affectationQcmProjet_viewTypes',
            'affectationQcmProjet_viewType',
            'affectationQcmProjets_data',
            'affectationQcmProjets_stats',
            'affectationQcmProjets_total',
            'affectationQcmProjets_filters',
            'affectationQcmProjet_instance',
            'affectationQcmProjet_title',
            'contextKey',
            'affectationQcmProjets_permissions',
            'affectationQcmProjets_permissionsByItem'
        );
    
        return [
            'affectationQcmProjets_data' => $affectationQcmProjets_data,
            'affectationQcmProjets_stats' => $affectationQcmProjets_stats,
            'affectationQcmProjets_total' => $affectationQcmProjets_total,
            'affectationQcmProjets_filters' => $affectationQcmProjets_filters,
            'affectationQcmProjet_instance' => $affectationQcmProjet_instance,
            'affectationQcmProjet_viewType' => $affectationQcmProjet_viewType,
            'affectationQcmProjet_viewTypes' => $affectationQcmProjet_viewTypes,
            'affectationQcmProjet_partialViewName' => $affectationQcmProjet_partialViewName,
            'contextKey' => $contextKey,
            'affectationQcmProjet_compact_value' => $compact_value,
            'affectationQcmProjets_permissions' => $affectationQcmProjets_permissions,
            'affectationQcmProjets_permissionsByItem' => $affectationQcmProjets_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $affectationQcmProjet_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $affectationQcmProjet_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($affectationQcmProjet_ids as $id) {
            $affectationQcmProjet = $this->find($id);
            $this->authorize('update', $affectationQcmProjet);
    
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
            'affectation_projet_id',
            'qcm_id'
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
    public function buildFieldMeta(AffectationQcmProjet $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgQcm\App\Requests\AffectationQcmProjetRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'affectation_qcm_projet',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'affectation_projet_id':
                 $values = (new \Modules\PkgRealisationProjets\Services\AffectationProjetService())
                    ->getAllForSelect($e->affectationProjet)
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
            case 'qcm_id':
                 $values = (new \Modules\PkgQcm\Services\QcmService())
                    ->getAllForSelect($e->qcm)
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
    public function applyInlinePatch(AffectationQcmProjet $e, array $changes): AffectationQcmProjet
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
    public function formatDisplayValues(AffectationQcmProjet $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'affectation_projet_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'affectationProjet'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'qcm_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'qcm'
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
