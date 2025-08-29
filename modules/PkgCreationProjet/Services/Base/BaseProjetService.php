<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe ProjetService pour gérer la persistance de l'entité Projet.
 */
class BaseProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour projets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'session_formation_id',
        'filiere_id',
        'titre',
        'travail_a_faire',
        'critere_de_travail',
        'formateur_id',
        'description'
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
     * Constructeur de la classe ProjetService.
     */
    public function __construct()
    {
        parent::__construct(new Projet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationProjet::projet.plural');
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
            $projet = $this->find($data['id']);
            $projet->fill($data);
        } else {
            $projet = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($projet->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $projet->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($projet->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($projet->id, $data);
            }
        }

        return $projet;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('projet');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('session_formation_id', $scopeVariables)) {


                    $sessionFormationService = new \Modules\PkgSessions\Services\SessionFormationService();
                    $sessionFormationIds = $this->getAvailableFilterValues('session_formation_id');
                    $sessionFormations = $sessionFormationService->getByIds($sessionFormationIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgSessions::sessionFormation.plural"), 
                        'session_formation_id', 
                        \Modules\PkgSessions\Models\SessionFormation::class, 
                        'code',
                        $sessionFormations
                    );
                }
            
            
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
     * Crée une nouvelle instance de projet.
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
    public function getProjetStats(): array
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

    public function clonerProjet(int $projetId)
    {
        $projet = $this->find($projetId);
        if (!$projet) {
            return false; 
        }
        $value =  $projet->save();
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
            'table' => 'PkgCreationProjet::projet._table',
            default => 'PkgCreationProjet::projet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('projet_view_type', $default_view_type);
        $projet_viewType = $this->viewState->get('projet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('projet_view_type') === 'widgets') {
            $this->viewState->set("scope.projet.visible", 1);
        }else{
            $this->viewState->remove("scope.projet.visible");
        }
        
        // Récupération des données
        $projets_data = $this->paginate($params);
        $projets_stats = $this->getprojetStats();
        $projets_total = $this->count();
        $projets_filters = $this->getFieldsFilterable();
        $projet_instance = $this->createInstance();
        $projet_viewTypes = $this->getViewTypes();
        $projet_partialViewName = $this->getPartialViewName($projet_viewType);
        $projet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.projet.stats', $projets_stats);
    
        $projets_permissions = [
            'clonerProjet-projet' => Auth::user()->can('clonerProjet-projet'),           
            
            'edit-projet' => Auth::user()->can('edit-projet'),
            'destroy-projet' => Auth::user()->can('destroy-projet'),
            'show-projet' => Auth::user()->can('show-projet'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $projets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($projets_data as $item) {
                $projets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'projet_viewTypes',
            'projet_viewType',
            'projets_data',
            'projets_stats',
            'projets_total',
            'projets_filters',
            'projet_instance',
            'projet_title',
            'contextKey',
            'projets_permissions',
            'projets_permissionsByItem'
        );
    
        return [
            'projets_data' => $projets_data,
            'projets_stats' => $projets_stats,
            'projets_total' => $projets_total,
            'projets_filters' => $projets_filters,
            'projet_instance' => $projet_instance,
            'projet_viewType' => $projet_viewType,
            'projet_viewTypes' => $projet_viewTypes,
            'projet_partialViewName' => $projet_partialViewName,
            'contextKey' => $contextKey,
            'projet_compact_value' => $compact_value,
            'projets_permissions' => $projets_permissions,
            'projets_permissionsByItem' => $projets_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $projet_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $projet_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($projet_ids as $id) {
            $projet = $this->find($id);
            $this->authorize('update', $projet);
    
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
            'titre',
            'Tache',
            'Livrable'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(Projet $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCreationProjet\App\Requests\ProjetRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'projet',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'titre':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'Tache':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'Livrable':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(Projet $e, array $changes): Projet
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
    public function formatDisplayValues(Projet $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'titre':
                    // Vue custom définie pour ce champ
                    $html = view('PkgCreationProjet::projet.custom.fields.titre', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'Tache':
                    // Vue custom définie pour ce champ
                    $html = view('PkgCreationProjet::projet.custom.fields.taches', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'Livrable':
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
