<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgFormation\Models\AnneeFormation;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * Classe AnneeFormationService pour gérer la persistance de l'entité AnneeFormation.
 */
class BaseAnneeFormationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour anneeFormations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'titre',
        'date_debut',
        'date_fin'
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
     * Constructeur de la classe AnneeFormationService.
     */
    public function __construct()
    {
        parent::__construct(new AnneeFormation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgFormation::anneeFormation.plural');
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
            $anneeFormation = $this->find($data['id']);
            $anneeFormation->fill($data);
        } else {
            $anneeFormation = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($anneeFormation->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $anneeFormation->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($anneeFormation->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($anneeFormation->id, $data);
            }
        }

        return $anneeFormation;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('anneeFormation');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de anneeFormation.
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
    public function getAnneeFormationStats(): array
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
            'table' => 'PkgFormation::anneeFormation._table',
            default => 'PkgFormation::anneeFormation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('anneeFormation_view_type', $default_view_type);
        $anneeFormation_viewType = $this->viewState->get('anneeFormation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('anneeFormation_view_type') === 'widgets') {
            $this->viewState->set("scope.anneeFormation.visible", 1);
        }else{
            $this->viewState->remove("scope.anneeFormation.visible");
        }
        
        // Récupération des données
        $anneeFormations_data = $this->paginate($params);
        $anneeFormations_stats = $this->getanneeFormationStats();
        $anneeFormations_total = $this->count();
        $anneeFormations_filters = $this->getFieldsFilterable();
        $anneeFormation_instance = $this->createInstance();
        $anneeFormation_viewTypes = $this->getViewTypes();
        $anneeFormation_partialViewName = $this->getPartialViewName($anneeFormation_viewType);
        $anneeFormation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.anneeFormation.stats', $anneeFormations_stats);
    
        $anneeFormations_permissions = [

            'edit-anneeFormation' => Auth::user()->can('edit-anneeFormation'),
            'destroy-anneeFormation' => Auth::user()->can('destroy-anneeFormation'),
            'show-anneeFormation' => Auth::user()->can('show-anneeFormation'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $anneeFormations_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($anneeFormations_data as $item) {
                $anneeFormations_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'anneeFormation_viewTypes',
            'anneeFormation_viewType',
            'anneeFormations_data',
            'anneeFormations_stats',
            'anneeFormations_total',
            'anneeFormations_filters',
            'anneeFormation_instance',
            'anneeFormation_title',
            'contextKey',
            'anneeFormations_permissions',
            'anneeFormations_permissionsByItem'
        );
    
        return [
            'anneeFormations_data' => $anneeFormations_data,
            'anneeFormations_stats' => $anneeFormations_stats,
            'anneeFormations_total' => $anneeFormations_total,
            'anneeFormations_filters' => $anneeFormations_filters,
            'anneeFormation_instance' => $anneeFormation_instance,
            'anneeFormation_viewType' => $anneeFormation_viewType,
            'anneeFormation_viewTypes' => $anneeFormation_viewTypes,
            'anneeFormation_partialViewName' => $anneeFormation_partialViewName,
            'contextKey' => $contextKey,
            'anneeFormation_compact_value' => $compact_value,
            'anneeFormations_permissions' => $anneeFormations_permissions,
            'anneeFormations_permissionsByItem' => $anneeFormations_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $anneeFormation_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $anneeFormation_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($anneeFormation_ids as $id) {
            $anneeFormation = $this->find($id);
            $this->authorize('update', $anneeFormation);
    
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
            'date_debut',
            'date_fin'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(AnneeFormation $e, string $field): array
    {
        $meta = [
            'entity'         => 'annee_formation',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
        ];

        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgFormation\App\Requests\AnneeFormationRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }
       switch ($field) {
            case 'titre':
                return $this->computeFieldMeta($e, $field, $meta, 'string', $validationRules);
            case 'date_debut':
                return $this->computeFieldMeta($e, $field, $meta, 'date', $validationRules);
            
            case 'date_fin':
                return $this->computeFieldMeta($e, $field, $meta, 'date', $validationRules);
            
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(AnneeFormation $e, array $changes): AnneeFormation
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
    public function formatDisplayValues(AnneeFormation $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'titre':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
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
