<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgFormation\Models\Formateur;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe FormateurService pour gérer la persistance de l'entité Formateur.
 */
class BaseFormateurService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour formateurs.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'matricule',
        'nom',
        'prenom',
        'prenom_arab',
        'nom_arab',
        'email',
        'tele_num',
        'adresse',
        'diplome',
        'echelle',
        'echelon',
        'profile_image',
        'user_id',
        'reference'
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
     * Constructeur de la classe FormateurService.
     */
    public function __construct()
    {
        parent::__construct(new Formateur());
        $this->fieldsFilterable = [];
        $this->title = __('PkgFormation::formateur.plural');
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
            $formateur = $this->find($data['id']);
            $formateur->fill($data);
        } else {
            $formateur = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($formateur->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $formateur->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($formateur->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($formateur->id, $data);
            }
        }

        return $formateur;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('formateur');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('specialites', $scopeVariables)) {

                    $specialiteService = new \Modules\PkgFormation\Services\SpecialiteService();
                    $specialiteIds = $this->getAvailableFilterValues('specialites.id');
                    $specialites = $specialiteService->getByIds($specialiteIds);

                    $this->fieldsFilterable[] = $this->generateManyToManyFilter(
                        __("PkgFormation::specialite.plural"), 
                        'specialite_id', 
                        \Modules\PkgFormation\Models\Specialite::class, 
                        'nom',
                        $specialites
                    );
                }
            
            
                if (!array_key_exists('groupes', $scopeVariables)) {

                    $groupeService = new \Modules\PkgApprenants\Services\GroupeService();
                    $groupeIds = $this->getAvailableFilterValues('groupes.id');
                    $groupes = $groupeService->getByIds($groupeIds);

                    $this->fieldsFilterable[] = $this->generateManyToManyFilter(
                        __("PkgApprenants::groupe.plural"), 
                        'groupe_id', 
                        \Modules\PkgApprenants\Models\Groupe::class, 
                        'code',
                        $groupes
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de formateur.
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
    public function getFormateurStats(): array
    {

        $stats = $this->initStats();

        
            $relationStatSpecialite = parent::getStatsByRelation(
                \Modules\PkgFormation\Models\Specialite::class,
                'formateurs',
                'nom'
            );
            $stats = array_merge($stats, $relationStatSpecialite);

        return $stats;
    }


    public function initPassword(int $formateurId)
    {
        $formateur = $this->find($formateurId);
        if (!$formateur) {
            return false; 
        }
        $value =  $formateur->save();
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
            'table' => 'PkgFormation::formateur._table',
            default => 'PkgFormation::formateur._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('formateur_view_type', $default_view_type);
        $formateur_viewType = $this->viewState->get('formateur_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('formateur_view_type') === 'widgets') {
            $this->viewState->set("scope.formateur.visible", 1);
        }else{
            $this->viewState->remove("scope.formateur.visible");
        }
        
        // Récupération des données
        $formateurs_data = $this->paginate($params);
        $formateurs_stats = $this->getformateurStats();
        $formateurs_total = $this->count();
        $formateurs_filters = $this->getFieldsFilterable();
        $formateur_instance = $this->createInstance();
        $formateur_viewTypes = $this->getViewTypes();
        $formateur_partialViewName = $this->getPartialViewName($formateur_viewType);
        $formateur_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.formateur.stats', $formateurs_stats);
    
        $formateurs_permissions = [
            'initPassword-formateur' => Auth::user()->can('initPassword-formateur'),           
            
            'edit-formateur' => Auth::user()->can('edit-formateur'),
            'destroy-formateur' => Auth::user()->can('destroy-formateur'),
            'show-formateur' => Auth::user()->can('show-formateur'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $formateurs_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($formateurs_data as $item) {
                $formateurs_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'formateur_viewTypes',
            'formateur_viewType',
            'formateurs_data',
            'formateurs_stats',
            'formateurs_total',
            'formateurs_filters',
            'formateur_instance',
            'formateur_title',
            'contextKey',
            'formateurs_permissions',
            'formateurs_permissionsByItem'
        );
    
        return [
            'formateurs_data' => $formateurs_data,
            'formateurs_stats' => $formateurs_stats,
            'formateurs_total' => $formateurs_total,
            'formateurs_filters' => $formateurs_filters,
            'formateur_instance' => $formateur_instance,
            'formateur_viewType' => $formateur_viewType,
            'formateur_viewTypes' => $formateur_viewTypes,
            'formateur_partialViewName' => $formateur_partialViewName,
            'contextKey' => $contextKey,
            'formateur_compact_value' => $compact_value,
            'formateurs_permissions' => $formateurs_permissions,
            'formateurs_permissionsByItem' => $formateurs_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $formateur_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $formateur_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($formateur_ids as $id) {
            $formateur = $this->find($id);
            $this->authorize('update', $formateur);
    
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
            'nom',
            'prenom',
            'specialites',
            'groupes'
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
    public function buildFieldMeta(Formateur $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgFormation\App\Requests\FormateurRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'formateur',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'nom':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'prenom':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'specialites':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'groupes':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(Formateur $e, array $changes): Formateur
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
    public function formatDisplayValues(Formateur $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'nom':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'prenom':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'specialites':
                    // fallback string simple
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'groupes':
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
