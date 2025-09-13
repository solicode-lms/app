<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationUa;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe RealisationUaService pour gérer la persistance de l'entité RealisationUa.
 */
class BaseRealisationUaService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationUas.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'unite_apprentissage_id',
        'realisation_micro_competence_id',
        'etat_realisation_ua_id',
        'progression_cache',
        'note_cache',
        'bareme_cache',
        'dernier_update',
        'date_debut',
        'date_fin',
        'commentaire_formateur',
        'progression_ideal_cache',
        'taux_rythme_cache'
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
     * Constructeur de la classe RealisationUaService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationUa());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::realisationUa.plural');
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
            $realisationUa = $this->find($data['id']);
            $realisationUa->fill($data);
        } else {
            $realisationUa = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($realisationUa->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $realisationUa->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($realisationUa->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($realisationUa->id, $data);
            }
        }

        return $realisationUa;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationUa');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('unite_apprentissage_id', $scopeVariables)) {


                    $uniteApprentissageService = new \Modules\PkgCompetences\Services\UniteApprentissageService();
                    $uniteApprentissageIds = $this->getAvailableFilterValues('unite_apprentissage_id');
                    $uniteApprentissages = $uniteApprentissageService->getByIds($uniteApprentissageIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::uniteApprentissage.plural"), 
                        'unite_apprentissage_id', 
                        \Modules\PkgCompetences\Models\UniteApprentissage::class, 
                        'code',
                        $uniteApprentissages
                    );
                }
            
            
                if (!array_key_exists('realisation_micro_competence_id', $scopeVariables)) {


                    $realisationMicroCompetenceService = new \Modules\PkgApprentissage\Services\RealisationMicroCompetenceService();
                    $realisationMicroCompetenceIds = $this->getAvailableFilterValues('realisation_micro_competence_id');
                    $realisationMicroCompetences = $realisationMicroCompetenceService->getByIds($realisationMicroCompetenceIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprentissage::realisationMicroCompetence.plural"), 
                        'realisation_micro_competence_id', 
                        \Modules\PkgApprentissage\Models\RealisationMicroCompetence::class, 
                        'id',
                        $realisationMicroCompetences
                    );
                }
            
            
                if (!array_key_exists('etat_realisation_ua_id', $scopeVariables)) {


                    $etatRealisationUaService = new \Modules\PkgApprentissage\Services\EtatRealisationUaService();
                    $etatRealisationUaIds = $this->getAvailableFilterValues('etat_realisation_ua_id');
                    $etatRealisationUas = $etatRealisationUaService->getByIds($etatRealisationUaIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprentissage::etatRealisationUa.plural"), 
                        'etat_realisation_ua_id', 
                        \Modules\PkgApprentissage\Models\EtatRealisationUa::class, 
                        'nom',
                        $etatRealisationUas
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de realisationUa.
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
    public function getRealisationUaStats(): array
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
            'table' => 'PkgApprentissage::realisationUa._table',
            default => 'PkgApprentissage::realisationUa._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationUa_view_type', $default_view_type);
        $realisationUa_viewType = $this->viewState->get('realisationUa_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationUa_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationUa.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationUa.visible");
        }
        
        // Récupération des données
        $realisationUas_data = $this->paginate($params);
        $realisationUas_stats = $this->getrealisationUaStats();
        $realisationUas_total = $this->count();
        $realisationUas_filters = $this->getFieldsFilterable();
        $realisationUa_instance = $this->createInstance();
        $realisationUa_viewTypes = $this->getViewTypes();
        $realisationUa_partialViewName = $this->getPartialViewName($realisationUa_viewType);
        $realisationUa_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationUa.stats', $realisationUas_stats);
    
        $realisationUas_permissions = [

            'edit-realisationUa' => Auth::user()->can('edit-realisationUa'),
            'destroy-realisationUa' => Auth::user()->can('destroy-realisationUa'),
            'show-realisationUa' => Auth::user()->can('show-realisationUa'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationUas_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationUas_data as $item) {
                $realisationUas_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationUa_viewTypes',
            'realisationUa_viewType',
            'realisationUas_data',
            'realisationUas_stats',
            'realisationUas_total',
            'realisationUas_filters',
            'realisationUa_instance',
            'realisationUa_title',
            'contextKey',
            'realisationUas_permissions',
            'realisationUas_permissionsByItem'
        );
    
        return [
            'realisationUas_data' => $realisationUas_data,
            'realisationUas_stats' => $realisationUas_stats,
            'realisationUas_total' => $realisationUas_total,
            'realisationUas_filters' => $realisationUas_filters,
            'realisationUa_instance' => $realisationUa_instance,
            'realisationUa_viewType' => $realisationUa_viewType,
            'realisationUa_viewTypes' => $realisationUa_viewTypes,
            'realisationUa_partialViewName' => $realisationUa_partialViewName,
            'contextKey' => $contextKey,
            'realisationUa_compact_value' => $compact_value,
            'realisationUas_permissions' => $realisationUas_permissions,
            'realisationUas_permissionsByItem' => $realisationUas_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $realisationUa_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $realisationUa_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($realisationUa_ids as $id) {
            $realisationUa = $this->find($id);
            $this->authorize('update', $realisationUa);
    
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
    public function getFieldsEditable(): array
    {
        return [
            'unite_apprentissage_id',
            'etat_realisation_ua_id',
            'progression_cache',
            'note_cache'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(RealisationUa $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgApprentissage\App\Requests\RealisationUaRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'realisation_ua',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
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
            case 'etat_realisation_ua_id':
                 $values = (new \Modules\PkgApprentissage\Services\EtatRealisationUaService())
                    ->getAllForSelect($e->etatRealisationUa)
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
            case 'progression_cache':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            case 'note_cache':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(RealisationUa $e, array $changes): RealisationUa
    {
        $allowed = $this->getFieldsEditable();
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
    public function formatDisplayValues(RealisationUa $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'unite_apprentissage_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'uniteApprentissage'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'etat_realisation_ua_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'badge',
                        'relationName' => 'etatRealisationUa'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'progression_cache':
                    // Vue custom définie pour ce champ
                    $html = view('PkgApprentissage::realisationUa.custom.fields.progression_cache', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'note_cache':
                    // Vue custom définie pour ce champ
                    $html = view('PkgApprentissage::realisationUa.custom.fields.note_cache', [
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
