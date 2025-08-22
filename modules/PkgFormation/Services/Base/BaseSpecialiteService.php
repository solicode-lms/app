<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgFormation\Models\Specialite;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * Classe SpecialiteService pour gérer la persistance de l'entité Specialite.
 */
class BaseSpecialiteService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour specialites.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
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
     * Constructeur de la classe SpecialiteService.
     */
    public function __construct()
    {
        parent::__construct(new Specialite());
        $this->fieldsFilterable = [];
        $this->title = __('PkgFormation::specialite.plural');
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
            $specialite = $this->find($data['id']);
            $specialite->fill($data);
        } else {
            $specialite = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($specialite->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $specialite->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($specialite->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($specialite->id, $data);
            }
        }

        return $specialite;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('specialite');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('formateurs', $scopeVariables)) {

                    $formateurService = new \Modules\PkgFormation\Services\FormateurService();
                    $formateurIds = $this->getAvailableFilterValues('formateurs.id');
                    $formateurs = $formateurService->getByIds($formateurIds);

                    $this->fieldsFilterable[] = $this->generateManyToManyFilter(
                        __("PkgFormation::formateur.plural"), 
                        'formateur_id', 
                        \Modules\PkgFormation\Models\Formateur::class, 
                        'nom',
                        $formateurs
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de specialite.
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
    public function getSpecialiteStats(): array
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
            'table' => 'PkgFormation::specialite._table',
            default => 'PkgFormation::specialite._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('specialite_view_type', $default_view_type);
        $specialite_viewType = $this->viewState->get('specialite_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('specialite_view_type') === 'widgets') {
            $this->viewState->set("scope.specialite.visible", 1);
        }else{
            $this->viewState->remove("scope.specialite.visible");
        }
        
        // Récupération des données
        $specialites_data = $this->paginate($params);
        $specialites_stats = $this->getspecialiteStats();
        $specialites_total = $this->count();
        $specialites_filters = $this->getFieldsFilterable();
        $specialite_instance = $this->createInstance();
        $specialite_viewTypes = $this->getViewTypes();
        $specialite_partialViewName = $this->getPartialViewName($specialite_viewType);
        $specialite_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.specialite.stats', $specialites_stats);
    
        $specialites_permissions = [

            'edit-specialite' => Auth::user()->can('edit-specialite'),
            'destroy-specialite' => Auth::user()->can('destroy-specialite'),
            'show-specialite' => Auth::user()->can('show-specialite'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $specialites_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($specialites_data as $item) {
                $specialites_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'specialite_viewTypes',
            'specialite_viewType',
            'specialites_data',
            'specialites_stats',
            'specialites_total',
            'specialites_filters',
            'specialite_instance',
            'specialite_title',
            'contextKey',
            'specialites_permissions',
            'specialites_permissionsByItem'
        );
    
        return [
            'specialites_data' => $specialites_data,
            'specialites_stats' => $specialites_stats,
            'specialites_total' => $specialites_total,
            'specialites_filters' => $specialites_filters,
            'specialite_instance' => $specialite_instance,
            'specialite_viewType' => $specialite_viewType,
            'specialite_viewTypes' => $specialite_viewTypes,
            'specialite_partialViewName' => $specialite_partialViewName,
            'contextKey' => $contextKey,
            'specialite_compact_value' => $compact_value,
            'specialites_permissions' => $specialites_permissions,
            'specialites_permissionsByItem' => $specialites_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $specialite_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $specialite_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($specialite_ids as $id) {
            $specialite = $this->find($id);
            $this->authorize('update', $specialite);
    
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
            'nom',
            'formateurs'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(Specialite $e, string $field): array
    {
        $meta = [
            'entity'         => 'specialite',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
        ];

        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgFormation\App\Requests\SpecialiteRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }
       switch ($field) {
            case 'nom':
                return $this->computeFieldMeta($e, $field, $meta, 'string', $validationRules);
            case 'formateurs':
                return $this->computeFieldMeta($e, $field, $meta, 'string', $validationRules);
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(Specialite $e, array $changes): Specialite
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
    public function formatDisplayValues(Specialite $e, array $fields): array
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
                case 'formateurs':
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
