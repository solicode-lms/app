<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\Models\RealisationQcm;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe RealisationQcmService pour gérer la persistance de l'entité RealisationQcm.
 */
class BaseRealisationQcmService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationQcms.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'reference',
        'affectation_qcm_projet_id',
        'qcm_id',
        'apprenant_id',
        'etat_realisation_qcm_id',
        'date_debut',
        'date_fin',
        'date_soumission',
        'date_validation',
        'note_obtenu',
        'statut'
    ];



    public function editableFieldsByRoles(): array
    {
        return [
          'affectation_qcm_projet_id' => ['admin'],
          'qcm_id' => ['admin'],
          'apprenant_id' => ['admin'],
          'date_debut' => ['admin'],
          'date_fin' => ['admin'],
          'date_soumission' => ['admin'],
          'date_validation' => ['admin'],
          'note_obtenu' => ['admin']
        
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
     * Constructeur de la classe RealisationQcmService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationQcm());
        $this->fieldsFilterable = [];
        $this->title = __('PkgQcm::realisationQcm.plural');
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
            $realisationQcm = $this->find($data['id']);
            $realisationQcm->fill($data);
        } else {
            $realisationQcm = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($realisationQcm->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $realisationQcm->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($realisationQcm->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($realisationQcm->id, $data);
            }
        }

        return $realisationQcm;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationQcm');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('affectation_qcm_projet_id', $scopeVariables)) {


                    $affectationQcmProjetService = new \Modules\PkgQcm\Services\AffectationQcmProjetService();
                    $affectationQcmProjetIds = $this->getAvailableFilterValues('affectation_qcm_projet_id');
                    $affectationQcmProjets = $affectationQcmProjetService->getByIds($affectationQcmProjetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgQcm::affectationQcmProjet.plural"), 
                        'affectation_qcm_projet_id', 
                        \Modules\PkgQcm\Models\AffectationQcmProjet::class, 
                        'id',
                        $affectationQcmProjets
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
            
            
                if (!array_key_exists('apprenant_id', $scopeVariables)) {


                    $apprenantService = new \Modules\PkgApprenants\Services\ApprenantService();
                    $apprenantIds = $this->getAvailableFilterValues('apprenant_id');
                    $apprenants = $apprenantService->getByIds($apprenantIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprenants::apprenant.plural"), 
                        'apprenant_id', 
                        \Modules\PkgApprenants\Models\Apprenant::class, 
                        'nom',
                        $apprenants
                    );
                }
            
            
                if (!array_key_exists('etat_realisation_qcm_id', $scopeVariables)) {


                    $etatRealisationQcmService = new \Modules\PkgQcm\Services\EtatRealisationQcmService();
                    $etatRealisationQcmIds = $this->getAvailableFilterValues('etat_realisation_qcm_id');
                    $etatRealisationQcms = $etatRealisationQcmService->getByIds($etatRealisationQcmIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgQcm::etatRealisationQcm.plural"), 
                        'etat_realisation_qcm_id', 
                        \Modules\PkgQcm\Models\EtatRealisationQcm::class, 
                        'titre',
                        $etatRealisationQcms
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de realisationQcm.
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
    public function getRealisationQcmStats(): array
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

    public function initQcm(int $realisationQcmId)
    {
        $realisationQcm = $this->find($realisationQcmId);
        if (!$realisationQcm) {
            return false; 
        }
        $value =  $realisationQcm->save();
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
            'table' => 'PkgQcm::realisationQcm._table',
            default => 'PkgQcm::realisationQcm._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationQcm_view_type', $default_view_type);
        $realisationQcm_viewType = $this->viewState->get('realisationQcm_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationQcm_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationQcm.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationQcm.visible");
        }
        
        // Récupération des données
        $realisationQcms_data = $this->paginate($params);
        $realisationQcms_stats = $this->getrealisationQcmStats();
        $realisationQcms_total = $this->count();
        $realisationQcms_filters = $this->getFieldsFilterable();
        $realisationQcm_instance = $this->createInstance();
        $realisationQcm_viewTypes = $this->getViewTypes();
        $realisationQcm_partialViewName = $this->getPartialViewName($realisationQcm_viewType);
        $realisationQcm_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationQcm.stats', $realisationQcms_stats);
    
        $realisationQcms_permissions = [
            'passer-qcm' => Auth::user()->can('passer-qcm'),
            'initQcm-realisationQcm' => Auth::user()->can('initQcm-realisationQcm'),           
            
            'edit-realisationQcm' => Auth::user()->can('edit-realisationQcm'),
            'destroy-realisationQcm' => Auth::user()->can('destroy-realisationQcm'),
            'show-realisationQcm' => Auth::user()->can('show-realisationQcm'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationQcms_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationQcms_data as $item) {
                $realisationQcms_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationQcm_viewTypes',
            'realisationQcm_viewType',
            'realisationQcms_data',
            'realisationQcms_stats',
            'realisationQcms_total',
            'realisationQcms_filters',
            'realisationQcm_instance',
            'realisationQcm_title',
            'contextKey',
            'realisationQcms_permissions',
            'realisationQcms_permissionsByItem'
        );
    
        return [
            'realisationQcms_data' => $realisationQcms_data,
            'realisationQcms_stats' => $realisationQcms_stats,
            'realisationQcms_total' => $realisationQcms_total,
            'realisationQcms_filters' => $realisationQcms_filters,
            'realisationQcm_instance' => $realisationQcm_instance,
            'realisationQcm_viewType' => $realisationQcm_viewType,
            'realisationQcm_viewTypes' => $realisationQcm_viewTypes,
            'realisationQcm_partialViewName' => $realisationQcm_partialViewName,
            'contextKey' => $contextKey,
            'realisationQcm_compact_value' => $compact_value,
            'realisationQcms_permissions' => $realisationQcms_permissions,
            'realisationQcms_permissionsByItem' => $realisationQcms_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $realisationQcm_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $realisationQcm_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($realisationQcm_ids as $id) {
            $realisationQcm = $this->find($id);
            $this->authorize('update', $realisationQcm);
    
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
            'qcm_id',
            'apprenant_id',
            'etat_realisation_qcm_id',
            'note_obtenu'
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
    public function buildFieldMeta(RealisationQcm $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgQcm\App\Requests\RealisationQcmRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'realisation_qcm',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
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
            case 'apprenant_id':
                 $values = (new \Modules\PkgApprenants\Services\ApprenantService())
                    ->getAllForSelect($e->apprenant)
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
            case 'etat_realisation_qcm_id':
                 $values = (new \Modules\PkgQcm\Services\EtatRealisationQcmService())
                    ->getAllForSelect($e->etatRealisationQcm)
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
            case 'note_obtenu':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(RealisationQcm $e, array $changes): RealisationQcm
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
    public function formatDisplayValues(RealisationQcm $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'qcm_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'qcm'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'apprenant_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'apprenant'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'etat_realisation_qcm_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'badge',
                        'relationName' => 'etatRealisationQcm'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'note_obtenu':
                    // Vue custom définie pour ce champ
                    $html = view('PkgQcm::realisationQcm.custom.fields.note_obtenu', [
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
