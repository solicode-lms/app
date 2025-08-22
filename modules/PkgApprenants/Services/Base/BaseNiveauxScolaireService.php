<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprenants\Models\NiveauxScolaire;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * Classe NiveauxScolaireService pour gérer la persistance de l'entité NiveauxScolaire.
 */
class BaseNiveauxScolaireService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour niveauxScolaires.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
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
     * Constructeur de la classe NiveauxScolaireService.
     */
    public function __construct()
    {
        parent::__construct(new NiveauxScolaire());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprenants::niveauxScolaire.plural');
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
            $niveauxScolaire = $this->find($data['id']);
            $niveauxScolaire->fill($data);
        } else {
            $niveauxScolaire = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($niveauxScolaire->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $niveauxScolaire->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($niveauxScolaire->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($niveauxScolaire->id, $data);
            }
        }

        return $niveauxScolaire;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('niveauxScolaire');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de niveauxScolaire.
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
    public function getNiveauxScolaireStats(): array
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
            'table' => 'PkgApprenants::niveauxScolaire._table',
            default => 'PkgApprenants::niveauxScolaire._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('niveauxScolaire_view_type', $default_view_type);
        $niveauxScolaire_viewType = $this->viewState->get('niveauxScolaire_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('niveauxScolaire_view_type') === 'widgets') {
            $this->viewState->set("scope.niveauxScolaire.visible", 1);
        }else{
            $this->viewState->remove("scope.niveauxScolaire.visible");
        }
        
        // Récupération des données
        $niveauxScolaires_data = $this->paginate($params);
        $niveauxScolaires_stats = $this->getniveauxScolaireStats();
        $niveauxScolaires_total = $this->count();
        $niveauxScolaires_filters = $this->getFieldsFilterable();
        $niveauxScolaire_instance = $this->createInstance();
        $niveauxScolaire_viewTypes = $this->getViewTypes();
        $niveauxScolaire_partialViewName = $this->getPartialViewName($niveauxScolaire_viewType);
        $niveauxScolaire_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.niveauxScolaire.stats', $niveauxScolaires_stats);
    
        $niveauxScolaires_permissions = [

            'edit-niveauxScolaire' => Auth::user()->can('edit-niveauxScolaire'),
            'destroy-niveauxScolaire' => Auth::user()->can('destroy-niveauxScolaire'),
            'show-niveauxScolaire' => Auth::user()->can('show-niveauxScolaire'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $niveauxScolaires_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($niveauxScolaires_data as $item) {
                $niveauxScolaires_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'niveauxScolaire_viewTypes',
            'niveauxScolaire_viewType',
            'niveauxScolaires_data',
            'niveauxScolaires_stats',
            'niveauxScolaires_total',
            'niveauxScolaires_filters',
            'niveauxScolaire_instance',
            'niveauxScolaire_title',
            'contextKey',
            'niveauxScolaires_permissions',
            'niveauxScolaires_permissionsByItem'
        );
    
        return [
            'niveauxScolaires_data' => $niveauxScolaires_data,
            'niveauxScolaires_stats' => $niveauxScolaires_stats,
            'niveauxScolaires_total' => $niveauxScolaires_total,
            'niveauxScolaires_filters' => $niveauxScolaires_filters,
            'niveauxScolaire_instance' => $niveauxScolaire_instance,
            'niveauxScolaire_viewType' => $niveauxScolaire_viewType,
            'niveauxScolaire_viewTypes' => $niveauxScolaire_viewTypes,
            'niveauxScolaire_partialViewName' => $niveauxScolaire_partialViewName,
            'contextKey' => $contextKey,
            'niveauxScolaire_compact_value' => $compact_value,
            'niveauxScolaires_permissions' => $niveauxScolaires_permissions,
            'niveauxScolaires_permissionsByItem' => $niveauxScolaires_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $niveauxScolaire_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $niveauxScolaire_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($niveauxScolaire_ids as $id) {
            $niveauxScolaire = $this->find($id);
            $this->authorize('update', $niveauxScolaire);
    
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
            'code'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(NiveauxScolaire $e, string $field): array
    {
        $meta = [
            'entity'         => 'niveaux_scolaire',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
        ];

        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgApprenants\App\Requests\NiveauxScolaireRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }
       switch ($field) {
            case 'code':
                return $this->computeFieldMeta($e, $field, $meta, 'string', $validationRules);
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(NiveauxScolaire $e, array $changes): NiveauxScolaire
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
    public function formatDisplayValues(NiveauxScolaire $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'code':
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
