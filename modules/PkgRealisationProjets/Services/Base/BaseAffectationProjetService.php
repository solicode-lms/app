<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * Classe AffectationProjetService pour gérer la persistance de l'entité AffectationProjet.
 */
class BaseAffectationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour affectationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'projet_id',
        'groupe_id',
        'sous_groupe_id',
        'annee_formation_id',
        'date_debut',
        'date_fin',
        'is_formateur_evaluateur',
        'echelle_note_cible',
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
     * Constructeur de la classe AffectationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new AffectationProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationProjets::affectationProjet.plural');
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
            $affectationProjet = $this->find($data['id']);
            $affectationProjet->fill($data);
        } else {
            $affectationProjet = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($affectationProjet->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $affectationProjet->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($affectationProjet->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($affectationProjet->id, $data);
            }
        }

        return $affectationProjet;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('affectationProjet');
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
            
            
                if (!array_key_exists('groupe_id', $scopeVariables)) {


                    $groupeService = new \Modules\PkgApprenants\Services\GroupeService();
                    $groupeIds = $this->getAvailableFilterValues('groupe_id');
                    $groupes = $groupeService->getByIds($groupeIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprenants::groupe.plural"), 
                        'groupe_id', 
                        \Modules\PkgApprenants\Models\Groupe::class, 
                        'code',
                        $groupes
                    );
                }
            
            
                if (!array_key_exists('sous_groupe_id', $scopeVariables)) {


                    $sousGroupeService = new \Modules\PkgApprenants\Services\SousGroupeService();
                    $sousGroupeIds = $this->getAvailableFilterValues('sous_groupe_id');
                    $sousGroupes = $sousGroupeService->getByIds($sousGroupeIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprenants::sousGroupe.plural"), 
                        'sous_groupe_id', 
                        \Modules\PkgApprenants\Models\SousGroupe::class, 
                        'nom',
                        $sousGroupes
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de affectationProjet.
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
    public function getAffectationProjetStats(): array
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

    public function exportPV(int $affectationProjetId)
    {
        $affectationProjet = $this->find($affectationProjetId);
        if (!$affectationProjet) {
            return false; 
        }
        $value =  $affectationProjet->save();
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
            'table' => 'PkgRealisationProjets::affectationProjet._table',
            default => 'PkgRealisationProjets::affectationProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('affectationProjet_view_type', $default_view_type);
        $affectationProjet_viewType = $this->viewState->get('affectationProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('affectationProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.affectationProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.affectationProjet.visible");
        }
        
        // Récupération des données
        $affectationProjets_data = $this->paginate($params);
        $affectationProjets_stats = $this->getaffectationProjetStats();
        $affectationProjets_total = $this->count();
        $affectationProjets_filters = $this->getFieldsFilterable();
        $affectationProjet_instance = $this->createInstance();
        $affectationProjet_viewTypes = $this->getViewTypes();
        $affectationProjet_partialViewName = $this->getPartialViewName($affectationProjet_viewType);
        $affectationProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.affectationProjet.stats', $affectationProjets_stats);
    
        $affectationProjets_permissions = [
            'exportPV-affectationProjet' => Auth::user()->can('exportPV-affectationProjet'),           
            
            'edit-affectationProjet' => Auth::user()->can('edit-affectationProjet'),
            'destroy-affectationProjet' => Auth::user()->can('destroy-affectationProjet'),
            'show-affectationProjet' => Auth::user()->can('show-affectationProjet'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $affectationProjets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($affectationProjets_data as $item) {
                $affectationProjets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'affectationProjet_viewTypes',
            'affectationProjet_viewType',
            'affectationProjets_data',
            'affectationProjets_stats',
            'affectationProjets_total',
            'affectationProjets_filters',
            'affectationProjet_instance',
            'affectationProjet_title',
            'contextKey',
            'affectationProjets_permissions',
            'affectationProjets_permissionsByItem'
        );
    
        return [
            'affectationProjets_data' => $affectationProjets_data,
            'affectationProjets_stats' => $affectationProjets_stats,
            'affectationProjets_total' => $affectationProjets_total,
            'affectationProjets_filters' => $affectationProjets_filters,
            'affectationProjet_instance' => $affectationProjet_instance,
            'affectationProjet_viewType' => $affectationProjet_viewType,
            'affectationProjet_viewTypes' => $affectationProjet_viewTypes,
            'affectationProjet_partialViewName' => $affectationProjet_partialViewName,
            'contextKey' => $contextKey,
            'affectationProjet_compact_value' => $compact_value,
            'affectationProjets_permissions' => $affectationProjets_permissions,
            'affectationProjets_permissionsByItem' => $affectationProjets_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $affectationProjet_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $affectationProjet_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($affectationProjet_ids as $id) {
            $affectationProjet = $this->find($id);
            $this->authorize('update', $affectationProjet);
    
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
            'projet_id',
            'groupe_id',
            'sous_groupe_id',
            'date_debut',
            'date_fin',
            'evaluateurs'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(AffectationProjet $e, string $field): array
    {
        $meta = [
            'entity'         => 'affectation_projet',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
        ];

        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgRealisationProjets\App\Requests\AffectationProjetRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }
       switch ($field) {
            case 'projet_id':
                 $values = (new \Modules\PkgCreationProjet\Services\ProjetService())
                    ->getAllForSelect($e->projet)
                    ->map(fn($entity) => [
                        'value' => (int) $entity->id,
                        'label' => (string) $entity,
                    ])
                    ->toArray();

                return $this->computeFieldMeta($e, $field, $meta, 'select', $validationRules, [
                    'required' => true,
                    'options'  => [
                        'source' => 'static',
                        'values' => $values,
                    ],
                ]);
            case 'groupe_id':
                 $values = (new \Modules\PkgApprenants\Services\GroupeService())
                    ->getAllForSelect($e->groupe)
                    ->map(fn($entity) => [
                        'value' => (int) $entity->id,
                        'label' => (string) $entity,
                    ])
                    ->toArray();

                return $this->computeFieldMeta($e, $field, $meta, 'select', $validationRules, [
                    'required' => true,
                    'options'  => [
                        'source' => 'static',
                        'values' => $values,
                    ],
                ]);
            case 'sous_groupe_id':
                 $values = (new \Modules\PkgApprenants\Services\SousGroupeService())
                    ->getAllForSelect($e->sousGroupe)
                    ->map(fn($entity) => [
                        'value' => (int) $entity->id,
                        'label' => (string) $entity,
                    ])
                    ->toArray();

                return $this->computeFieldMeta($e, $field, $meta, 'select', $validationRules, [
                    'required' => true,
                    'options'  => [
                        'source' => 'static',
                        'values' => $values,
                    ],
                ]);
            case 'date_debut':
                return $this->computeFieldMeta($e, $field, $meta, 'date', $validationRules);
            
            case 'date_fin':
                return $this->computeFieldMeta($e, $field, $meta, 'date', $validationRules);
            
            case 'evaluateurs':
                return $this->computeFieldMeta($e, $field, $meta, 'string', $validationRules);
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(AffectationProjet $e, array $changes): AffectationProjet
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
        Validator::make($filtered, $rules)->validate();

        $e->fill($filtered);
        $e->save();
        $e->refresh();
        return $e;
    }

    /**
     * Formatte les valeurs pour l’affichage inline
     */
    public function formatDisplayValues(AffectationProjet $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'projet_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'projet'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'groupe_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'groupe'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'sous_groupe_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'sousGroupe'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'date_debut':
                    $html = view('Core::fields_by_type.date', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'date_fin':
                    $html = view('Core::fields_by_type.date', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'evaluateurs':
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
