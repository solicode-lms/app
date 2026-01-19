<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationTache\Models\Tache;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe TacheService pour gérer la persistance de l'entité Tache.
 */
class BaseTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour taches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'priorite',
        'titre',
        'projet_id',
        'description',
        'dateDebut',
        'dateFin',
        'note',
        'phase_projet_id',
        'is_live_coding_task',
        'phase_evaluation_id',
        'chapitre_id',
        'mobilisation_ua_id'
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
     * Constructeur de la classe TacheService.
     */
    public function __construct()
    {
        parent::__construct(new Tache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationTache::tache.plural');
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
            $tache = $this->find($data['id']);
            $tache->fill($data);
        } else {
            $tache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($tache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $tache->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($tache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($tache->id, $data);
            }
        }

        return $tache;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('tache');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('projet_id', $scopeVariables)) {


                    $projetService = new \Modules\PkgCreationProjet\Services\ProjetService();
                    $projetIds = $this->getAvailableFilterValues('projet_id');
                    $projets = $projetService->getByIds($projetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationProjet::projet.plural"), 
                        'projet_id', 
                        \Modules\PkgCreationProjet\Models\Projet::class, 
                        'titre',
                        $projets
                    );
                }
            
            
                if (!array_key_exists('phase_projet_id', $scopeVariables)) {


                    $phaseProjetService = new \Modules\PkgCreationTache\Services\PhaseProjetService();
                    $phaseProjetIds = $this->getAvailableFilterValues('phase_projet_id');
                    $phaseProjets = $phaseProjetService->getByIds($phaseProjetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationTache::phaseProjet.plural"), 
                        'phase_projet_id', 
                        \Modules\PkgCreationTache\Models\PhaseProjet::class, 
                        'nom',
                        $phaseProjets
                    );
                }
            
            
                if (!array_key_exists('phase_evaluation_id', $scopeVariables)) {


                    $phaseEvaluationService = new \Modules\PkgCompetences\Services\PhaseEvaluationService();
                    $phaseEvaluationIds = $this->getAvailableFilterValues('phase_evaluation_id');
                    $phaseEvaluations = $phaseEvaluationService->getByIds($phaseEvaluationIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::phaseEvaluation.plural"), 
                        'phase_evaluation_id', 
                        \Modules\PkgCompetences\Models\PhaseEvaluation::class, 
                        'code',
                        $phaseEvaluations
                    );
                }
            
            
                if (!array_key_exists('chapitre_id', $scopeVariables)) {


                    $chapitreService = new \Modules\PkgCompetences\Services\ChapitreService();
                    $chapitreIds = $this->getAvailableFilterValues('chapitre_id');
                    $chapitres = $chapitreService->getByIds($chapitreIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::chapitre.plural"), 
                        'chapitre_id', 
                        \Modules\PkgCompetences\Models\Chapitre::class, 
                        'code',
                        $chapitres
                    );
                }
            
            
                if (!array_key_exists('mobilisation_ua_id', $scopeVariables)) {


                    $mobilisationUaService = new \Modules\PkgCreationProjet\Services\MobilisationUaService();
                    $mobilisationUaIds = $this->getAvailableFilterValues('mobilisation_ua_id');
                    $mobilisationUas = $mobilisationUaService->getByIds($mobilisationUaIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationProjet::mobilisationUa.plural"), 
                        'mobilisation_ua_id', 
                        \Modules\PkgCreationProjet\Models\MobilisationUa::class, 
                        'id',
                        $mobilisationUas
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de tache.
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
    public function getTacheStats(): array
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
            'table' => 'PkgCreationTache::tache._table',
            default => 'PkgCreationTache::tache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('tache_view_type', $default_view_type);
        $tache_viewType = $this->viewState->get('tache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('tache_view_type') === 'widgets') {
            $this->viewState->set("scope.tache.visible", 1);
        }else{
            $this->viewState->remove("scope.tache.visible");
        }
        
        // Récupération des données
        $taches_data = $this->paginate($params);
        $taches_stats = $this->gettacheStats();
        $taches_total = $this->count();
        $taches_filters = $this->getFieldsFilterable();
        $tache_instance = $this->createInstance();
        $tache_viewTypes = $this->getViewTypes();
        $tache_partialViewName = $this->getPartialViewName($tache_viewType);
        $tache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.tache.stats', $taches_stats);
    
        $taches_permissions = [

            'edit-tache' => Auth::user()->can('edit-tache'),
            'destroy-tache' => Auth::user()->can('destroy-tache'),
            'show-tache' => Auth::user()->can('show-tache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $taches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($taches_data as $item) {
                $taches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'tache_viewTypes',
            'tache_viewType',
            'taches_data',
            'taches_stats',
            'taches_total',
            'taches_filters',
            'tache_instance',
            'tache_title',
            'contextKey',
            'taches_permissions',
            'taches_permissionsByItem'
        );
    
        return [
            'taches_data' => $taches_data,
            'taches_stats' => $taches_stats,
            'taches_total' => $taches_total,
            'taches_filters' => $taches_filters,
            'tache_instance' => $tache_instance,
            'tache_viewType' => $tache_viewType,
            'tache_viewTypes' => $tache_viewTypes,
            'tache_partialViewName' => $tache_partialViewName,
            'contextKey' => $contextKey,
            'tache_compact_value' => $compact_value,
            'taches_permissions' => $taches_permissions,
            'taches_permissionsByItem' => $taches_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $tache_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $tache_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($tache_ids as $id) {
            $tache = $this->find($id);
            $this->authorize('update', $tache);
    
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
            'priorite',
            'titre',
            'note',
            'livrables'
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
    public function buildFieldMeta(Tache $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCreationTache\App\Requests\TacheRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'tache',
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

            case 'priorite':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            case 'titre':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'note':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            case 'livrables':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(Tache $e, array $changes): Tache
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
    public function formatDisplayValues(Tache $e, array $fields): array
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
                case 'priorite':
                    $html = view('Core::fields_by_type.integer', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'titre':
                    // Vue custom définie pour ce champ
                    $html = view('PkgCreationTache::tache.custom.fields.titre', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'note':
                    // Vue custom définie pour ce champ
                    $html = view('PkgCreationTache::tache.custom.fields.note', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'livrables':
                    // fallback string simple
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
