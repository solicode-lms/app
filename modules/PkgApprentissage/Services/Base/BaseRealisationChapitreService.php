<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationChapitre;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * Classe RealisationChapitreService pour gérer la persistance de l'entité RealisationChapitre.
 */
class BaseRealisationChapitreService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationChapitres.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'chapitre_id',
        'etat_realisation_chapitre_id',
        'date_debut',
        'date_fin',
        'realisation_ua_id',
        'realisation_tache_id',
        'commentaire_formateur'
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
     * Constructeur de la classe RealisationChapitreService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationChapitre());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::realisationChapitre.plural');
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
            $realisationChapitre = $this->find($data['id']);
            $realisationChapitre->fill($data);
        } else {
            $realisationChapitre = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($realisationChapitre->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $realisationChapitre->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($realisationChapitre->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($realisationChapitre->id, $data);
            }
        }

        return $realisationChapitre;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationChapitre');
        $this->fieldsFilterable = [];
        
            
                $microCompetenceService = new \Modules\PkgCompetences\Services\MicroCompetenceService();
                $microCompetenceIds = $this->getAvailableFilterValues('Chapitre.UniteApprentissage.Micro_competence_id');
                $microCompetences = $microCompetenceService->getByIds($microCompetenceIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgCompetences::microCompetence.plural"),
                    'Chapitre.UniteApprentissage.Micro_competence_id', 
                    \Modules\PkgCompetences\Models\MicroCompetence::class,
                    "id", 
                    "id",
                    $microCompetences,
                    "[name='chapitre_id']",
                    route('chapitres.getData'),
                    "uniteApprentissage.micro_competence_id"
                    
                );
            
            
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
            
            
                if (!array_key_exists('etat_realisation_chapitre_id', $scopeVariables)) {


                    $etatRealisationChapitreService = new \Modules\PkgApprentissage\Services\EtatRealisationChapitreService();
                    $etatRealisationChapitreIds = $this->getAvailableFilterValues('etat_realisation_chapitre_id');
                    $etatRealisationChapitres = $etatRealisationChapitreService->getByIds($etatRealisationChapitreIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprentissage::etatRealisationChapitre.plural"), 
                        'etat_realisation_chapitre_id', 
                        \Modules\PkgApprentissage\Models\EtatRealisationChapitre::class, 
                        'nom',
                        $etatRealisationChapitres
                    );
                }
            
            
                $groupeService = new \Modules\PkgApprenants\Services\GroupeService();
                $groupeIds = $this->getAvailableFilterValues('RealisationUa.RealisationMicroCompetence.Apprenant.Groupes.Id');
                $groupes = $groupeService->getByIds($groupeIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgApprenants::groupe.plural"),
                    'RealisationUa.RealisationMicroCompetence.Apprenant.Groupes.Id', 
                    \Modules\PkgApprenants\Models\Groupe::class,
                    "id", 
                    "id",
                    $groupes,
                    "[name='RealisationUa.RealisationMicroCompetence.Apprenant_id']",
                    route('apprenants.getData'),
                    "Groupes.Id"
                    
                );
            
            
                $apprenantService = new \Modules\PkgApprenants\Services\ApprenantService();
                $apprenantIds = $this->getAvailableFilterValues('RealisationUa.RealisationMicroCompetence.Apprenant_id');
                $apprenants = $apprenantService->getByIds($apprenantIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgApprenants::apprenant.plural"),
                    'RealisationUa.RealisationMicroCompetence.Apprenant_id', 
                    \Modules\PkgApprenants\Models\Apprenant::class,
                    "id", 
                    "id",
                    $apprenants
                );
            
            
                if (!array_key_exists('realisation_tache_id', $scopeVariables)) {


                    $realisationTacheService = new \Modules\PkgRealisationTache\Services\RealisationTacheService();
                    $realisationTacheIds = $this->getAvailableFilterValues('realisation_tache_id');
                    $realisationTaches = $realisationTacheService->getByIds($realisationTacheIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationTache::realisationTache.plural"), 
                        'realisation_tache_id', 
                        \Modules\PkgRealisationTache\Models\RealisationTache::class, 
                        'id',
                        $realisationTaches
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de realisationChapitre.
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
    public function getRealisationChapitreStats(): array
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
            'table' => 'PkgApprentissage::realisationChapitre._table',
            default => 'PkgApprentissage::realisationChapitre._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationChapitre_view_type', $default_view_type);
        $realisationChapitre_viewType = $this->viewState->get('realisationChapitre_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationChapitre_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationChapitre.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationChapitre.visible");
        }
        
        // Récupération des données
        $realisationChapitres_data = $this->paginate($params);
        $realisationChapitres_stats = $this->getrealisationChapitreStats();
        $realisationChapitres_total = $this->count();
        $realisationChapitres_filters = $this->getFieldsFilterable();
        $realisationChapitre_instance = $this->createInstance();
        $realisationChapitre_viewTypes = $this->getViewTypes();
        $realisationChapitre_partialViewName = $this->getPartialViewName($realisationChapitre_viewType);
        $realisationChapitre_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationChapitre.stats', $realisationChapitres_stats);
    
        $realisationChapitres_permissions = [

            'edit-realisationChapitre' => Auth::user()->can('edit-realisationChapitre'),
            'destroy-realisationChapitre' => Auth::user()->can('destroy-realisationChapitre'),
            'show-realisationChapitre' => Auth::user()->can('show-realisationChapitre'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationChapitres_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationChapitres_data as $item) {
                $realisationChapitres_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationChapitre_viewTypes',
            'realisationChapitre_viewType',
            'realisationChapitres_data',
            'realisationChapitres_stats',
            'realisationChapitres_total',
            'realisationChapitres_filters',
            'realisationChapitre_instance',
            'realisationChapitre_title',
            'contextKey',
            'realisationChapitres_permissions',
            'realisationChapitres_permissionsByItem'
        );
    
        return [
            'realisationChapitres_data' => $realisationChapitres_data,
            'realisationChapitres_stats' => $realisationChapitres_stats,
            'realisationChapitres_total' => $realisationChapitres_total,
            'realisationChapitres_filters' => $realisationChapitres_filters,
            'realisationChapitre_instance' => $realisationChapitre_instance,
            'realisationChapitre_viewType' => $realisationChapitre_viewType,
            'realisationChapitre_viewTypes' => $realisationChapitre_viewTypes,
            'realisationChapitre_partialViewName' => $realisationChapitre_partialViewName,
            'contextKey' => $contextKey,
            'realisationChapitre_compact_value' => $compact_value,
            'realisationChapitres_permissions' => $realisationChapitres_permissions,
            'realisationChapitres_permissionsByItem' => $realisationChapitres_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $realisationChapitre_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $realisationChapitre_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($realisationChapitre_ids as $id) {
            $realisationChapitre = $this->find($id);
            $this->authorize('update', $realisationChapitre);
    
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
            'chapitre_id',
            'etat_realisation_chapitre_id',
            'apprenant'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(RealisationChapitre $e, string $field): array
    {
        $meta = [
            'entity'         => 'realisation_chapitre',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
        ];

        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgApprentissage\App\Requests\RealisationChapitreRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }
       switch ($field) {
            case 'chapitre_id':
                 $values = (new \Modules\PkgCompetences\Services\ChapitreService())
                    ->getAllForSelect($e->chapitre)
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
            case 'etat_realisation_chapitre_id':
                 $values = (new \Modules\PkgApprentissage\Services\EtatRealisationChapitreService())
                    ->getAllForSelect($e->etatRealisationChapitre)
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
            case 'apprenant':
                return $this->computeFieldMeta($e, $field, $meta, 'string', $validationRules);
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(RealisationChapitre $e, array $changes): RealisationChapitre
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
    public function formatDisplayValues(RealisationChapitre $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'chapitre_id':
                    // Vue custom définie pour ce champ
                    $html = view('PkgApprentissage::realisationChapitre.custom.fields.chapitre', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'etat_realisation_chapitre_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'badge',
                        'relationName' => 'etatRealisationChapitre'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'apprenant':
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
