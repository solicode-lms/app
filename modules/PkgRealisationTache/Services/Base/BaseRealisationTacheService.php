<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\Services\Base;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\Core\Services\BaseService;
use Modules\PkgRealisationTache\App\Requests\RealisationTacheRequest;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;

/**
 * Classe RealisationTacheService pour gérer la persistance de l'entité RealisationTache.
 */
class BaseRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'tache_id',
        'etat_realisation_tache_id',
        'realisation_projet_id',
        'dateDebut',
        'dateFin',
        'remarque_evaluateur',
        'note',
        'is_live_coding',
        'remarques_formateur',
        'remarques_apprenant',
        'tache_affectation_id'
    ];

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
     * Constructeur de la classe RealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationTache::realisationTache.plural');
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
            $realisationTache = $this->find($data['id']);
            $realisationTache->fill($data);
        } else {
            $realisationTache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($realisationTache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $realisationTache->hasManyInputsToUpdate = [
                    'evaluationRealisationTaches' => 'evaluationRealisationTache-crud',
                    'realisationChapitres' => 'realisationChapitre-crud',
                    'realisationUaProjets' => 'realisationUaProjet-crud',
                    'realisationUaPrototypes' => 'realisationUaPrototype-crud',
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($realisationTache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($realisationTache->id, $data);
            }
        }

        return $realisationTache;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationTache');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('tache_id', $scopeVariables)) {


                    $tacheService = new \Modules\PkgCreationTache\Services\TacheService();
                    $tacheIds = $this->getAvailableFilterValues('tache_id');
                    $taches = $tacheService->getByIds($tacheIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationTache::tache.plural"), 
                        'tache_id', 
                        \Modules\PkgCreationTache\Models\Tache::class, 
                        'titre',
                        $taches
                    );
                }
            
            
                if (!array_key_exists('etat_realisation_tache_id', $scopeVariables)) {


                    $etatRealisationTacheService = new \Modules\PkgRealisationTache\Services\EtatRealisationTacheService();
                    $etatRealisationTacheIds = $this->getAvailableFilterValues('etat_realisation_tache_id');
                    $etatRealisationTaches = $etatRealisationTacheService->getByIds($etatRealisationTacheIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationTache::etatRealisationTache.plural"), 
                        'etat_realisation_tache_id', 
                        \Modules\PkgRealisationTache\Models\EtatRealisationTache::class, 
                        'nom',
                        $etatRealisationTaches
                    );
                }
            
            
                if (!array_key_exists('realisation_projet_id', $scopeVariables)) {


                    $realisationProjetService = new \Modules\PkgRealisationProjets\Services\RealisationProjetService();
                    $realisationProjetIds = $this->getAvailableFilterValues('realisation_projet_id');
                    $realisationProjets = $realisationProjetService->getByIds($realisationProjetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationProjets::realisationProjet.plural"), 
                        'realisation_projet_id', 
                        \Modules\PkgRealisationProjets\Models\RealisationProjet::class, 
                        'id',
                        $realisationProjets
                    );
                }
            
            
                if (!array_key_exists('tache_affectation_id', $scopeVariables)) {


                    $tacheAffectationService = new \Modules\PkgRealisationTache\Services\TacheAffectationService();
                    $tacheAffectationIds = $this->getAvailableFilterValues('tache_affectation_id');
                    $tacheAffectations = $tacheAffectationService->getByIds($tacheAffectationIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationTache::tacheAffectation.plural"), 
                        'tache_affectation_id', 
                        \Modules\PkgRealisationTache\Models\TacheAffectation::class, 
                        'id',
                        $tacheAffectations
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de realisationTache.
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
    public function getRealisationTacheStats(): array
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
                'icon'  => 'fas fa-table',
            ],
            [
                'type'  => 'table-evaluation',
                'label' => 'Vue évaluation',
                'icon'  => 'fas fa-clipboard-check',
            ],
        ];
    }

    /**
     * Retourne le nom de la vue partielle selon le type de vue sélectionné
     */
    public function getPartialViewName(string $viewType): string
    {
        return match ($viewType) {
            'table' => 'PkgRealisationTache::realisationTache._table',
            'table-evaluation' => 'PkgRealisationTache::realisationTache._table-evaluation',
            default => 'PkgRealisationTache::realisationTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationTache_view_type', $default_view_type);
        $realisationTache_viewType = $this->viewState->get('realisationTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationTache_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationTache.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationTache.visible");
        }
        
        // Récupération des données
        $realisationTaches_data = $this->paginate($params);
        $realisationTaches_stats = $this->getrealisationTacheStats();
        $realisationTaches_total = $this->count();
        $realisationTaches_filters = $this->getFieldsFilterable();
        $realisationTache_instance = $this->createInstance();
        $realisationTache_viewTypes = $this->getViewTypes();
        $realisationTache_partialViewName = $this->getPartialViewName($realisationTache_viewType);
        $realisationTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationTache.stats', $realisationTaches_stats);
    
        $realisationTaches_permissions = [
            'index-livrablesRealisation' => Auth::user()->can('index-livrablesRealisation'),
            'show-projet' => Auth::user()->can('show-projet'),

            'edit-realisationTache' => Auth::user()->can('edit-realisationTache'),
            'destroy-realisationTache' => Auth::user()->can('destroy-realisationTache'),
            'show-realisationTache' => Auth::user()->can('show-realisationTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationTaches_data as $item) {
                $realisationTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationTache_viewTypes',
            'realisationTache_viewType',
            'realisationTaches_data',
            'realisationTaches_stats',
            'realisationTaches_total',
            'realisationTaches_filters',
            'realisationTache_instance',
            'realisationTache_title',
            'contextKey',
            'realisationTaches_permissions',
            'realisationTaches_permissionsByItem'
        );
    
        return [
            'realisationTaches_data' => $realisationTaches_data,
            'realisationTaches_stats' => $realisationTaches_stats,
            'realisationTaches_total' => $realisationTaches_total,
            'realisationTaches_filters' => $realisationTaches_filters,
            'realisationTache_instance' => $realisationTache_instance,
            'realisationTache_viewType' => $realisationTache_viewType,
            'realisationTache_viewTypes' => $realisationTache_viewTypes,
            'realisationTache_partialViewName' => $realisationTache_partialViewName,
            'contextKey' => $contextKey,
            'realisationTache_compact_value' => $compact_value,
            'realisationTaches_permissions' => $realisationTaches_permissions,
            'realisationTaches_permissionsByItem' => $realisationTaches_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $realisationTache_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $realisationTache_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($realisationTache_ids as $id) {
            $realisationTache = $this->find($id);
            $this->authorize('update', $realisationTache);
    
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
            'etat_realisation_tache_id',
            'dateDebut',
            'dateFin',
            'note',
            'is_live_coding',
            'remarques_formateur',
            'remarques_apprenant',
            'remarque_evaluateur',
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(RealisationTache $e, string $field): array
    {
        $meta = [
            'entity' => 'realisation_tache',
            'id'     => $e->id,
            'field'  => $field,
            'writable' => in_array($field, $this->getFieldsEditable()),
            'etag'   => $this->etag($e),
            'schema_version' => 'v1',
        ];

        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = app(RealisationTacheRequest::class)->rules();
        $validationRules = $rules[$field] ?? [];

        switch ($field) {
            case 'etat_realisation_tache_id':


                $options = app(EtatRealisationTacheService::class)
                    ->getAllForSelect($e->etatRealisationTache); 
                $values = $options->map(fn($entity, $id) => [
                    'value' => (int) $entity->id,
                    'label' => (string) $entity,
                ])->values();

                $meta += [
                    'type' => 'select',
                    'required' => true,
                    'options' => [ 'source' => 'static','values' => $values],
                    'validation' => $validationRules,
                    'value' => $e->etat_realisation_tache_id,
                ];
                break;

            case 'dateDebut':
            case 'dateFin':
                $meta += [
                    'type' => 'date',
                    'validation' => $validationRules,
                    'value' => optional($e->$field)->format('Y-m-d'),
                ];
                break;

            case 'note':
                $meta += [
                    'type' => 'number',
                    'validation' => $validationRules,
                    'value' => $e->note,
                ];
                break;

            case 'is_live_coding':
                $meta += [
                    'type' => 'boolean',
                    'validation' => $validationRules,
                    'value' => (bool) $e->is_live_coding,
                ];
                break;

            case 'remarques_formateur':
            case 'remarques_apprenant':
            case 'remarque_evaluateur':
                $meta += [
                    'type' => 'text',
                    'validation' => $validationRules,
                    'value' => $e->$field,
                ];
                break;

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }

        return $meta;
    }

    /**
     * Méthode générique qui calcule la meta en fonction du type et des paramètres.
     *
     * @param  RealisationTache $e
     * @param  string           $field
     * @param  array            $baseMeta
     * @param  string           $type
     * @param  array            $validationRules
     * @param  array            $extra
     * @return array
     */
    protected function computeFieldMeta(RealisationTache $e, string $field, array $baseMeta, string $type, array $validationRules, array $extra = []): array
    {
        // 🔹 Calcul automatique de la valeur en fonction du type
        $value = match ($type) {
            'date'    => optional($e->$field)->format('Y-m-d'),
            'boolean' => (bool) $e->$field,
            default   => $e->$field,
        };

        return array_merge($baseMeta, [
            'type'       => $type,
            'validation' => $validationRules,
            'value'      => $value,
        ], $extra);
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(RealisationTache $e, array $changes): RealisationTache
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

        // Validator::make($filtered, $rules)->validate();

        $e->fill($filtered);
        $e->save();
        $e->refresh();
        return $e;
    }

    /**
     * Formatte les valeurs pour l’affichage inline
     */
    public function formatDisplayValues(RealisationTache $e, array $fields): array
    {
      
        $out = [];
        foreach ($fields as $field) {
            switch ($field) {
                case 'etat_realisation_tache_id':
                    // $label = optional($e->etatRealisationTache)->libelle ?? '—';
                    // $code  = optional($e->etatRealisationTache->workflowTache)->code;
                    // $palette = ['TODO' => 'secondary', 'DOING' => 'warning', 'APPROVED' => 'success'];
                    // $out[$field] = [
                    //     'text'  => $label,
                    //     'badge' => $palette[$code] ?? 'secondary',
                    // ];
                     // ⚡ Utiliser un Blade partial pour produire le HTML final
                    $html = view('PkgRealisationTache::realisationTache.custom.fields.etatRealisationTache', [
                        'entity' => $e
                    ])->render();

                $out[$field] = [
                    'html' => $html, // rendu complet du partial
                ];
                    break;

                case 'dateDebut':
                case 'dateFin':
                    $out[$field] = ['text' => optional($e->$field)->format('Y-m-d') ?? '—'];
                    break;

                case 'note':
                    $out[$field] = ['text' => $e->note !== null ? $e->note . '/20' : '—'];
                    break;

                case 'is_live_coding':
                    $out[$field] = ['text' => $e->is_live_coding ? 'Oui' : 'Non'];
                    break;

                case 'remarques_formateur':
                case 'remarques_apprenant':
                case 'remarque_evaluateur':
                    $out[$field] = ['text' => (string) $e->$field ?? '—'];
                    break;

                default:
                    $out[$field] = ['text' => (string) data_get($e, $field, '—')];
            }
        }
        return $out;
    }


}
