<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprenants\Models\ApprenantKonosy;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * Classe ApprenantKonosyService pour gérer la persistance de l'entité ApprenantKonosy.
 */
class BaseApprenantKonosyService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour apprenantKonosies.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'MatriculeEtudiant',
        'Nom',
        'Prenom',
        'Sexe',
        'EtudiantActif',
        'Diplome',
        'Principale',
        'LibelleLong',
        'CodeDiplome',
        'DateNaissance',
        'DateInscription',
        'LieuNaissance',
        'CIN',
        'NTelephone',
        'Adresse',
        'Nationalite',
        'Nom_Arabe',
        'Prenom_Arabe',
        'NiveauScolaire'
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
     * Constructeur de la classe ApprenantKonosyService.
     */
    public function __construct()
    {
        parent::__construct(new ApprenantKonosy());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprenants::apprenantKonosy.plural');
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
            $apprenantKonosy = $this->find($data['id']);
            $apprenantKonosy->fill($data);
        } else {
            $apprenantKonosy = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($apprenantKonosy->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $apprenantKonosy->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($apprenantKonosy->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($apprenantKonosy->id, $data);
            }
        }

        return $apprenantKonosy;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('apprenantKonosy');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de apprenantKonosy.
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
    public function getApprenantKonosyStats(): array
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
            'table' => 'PkgApprenants::apprenantKonosy._table',
            default => 'PkgApprenants::apprenantKonosy._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('apprenantKonosy_view_type', $default_view_type);
        $apprenantKonosy_viewType = $this->viewState->get('apprenantKonosy_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('apprenantKonosy_view_type') === 'widgets') {
            $this->viewState->set("scope.apprenantKonosy.visible", 1);
        }else{
            $this->viewState->remove("scope.apprenantKonosy.visible");
        }
        
        // Récupération des données
        $apprenantKonosies_data = $this->paginate($params);
        $apprenantKonosies_stats = $this->getapprenantKonosyStats();
        $apprenantKonosies_total = $this->count();
        $apprenantKonosies_filters = $this->getFieldsFilterable();
        $apprenantKonosy_instance = $this->createInstance();
        $apprenantKonosy_viewTypes = $this->getViewTypes();
        $apprenantKonosy_partialViewName = $this->getPartialViewName($apprenantKonosy_viewType);
        $apprenantKonosy_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.apprenantKonosy.stats', $apprenantKonosies_stats);
    
        $apprenantKonosies_permissions = [

            'edit-apprenantKonosy' => Auth::user()->can('edit-apprenantKonosy'),
            'destroy-apprenantKonosy' => Auth::user()->can('destroy-apprenantKonosy'),
            'show-apprenantKonosy' => Auth::user()->can('show-apprenantKonosy'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $apprenantKonosies_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($apprenantKonosies_data as $item) {
                $apprenantKonosies_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'apprenantKonosy_viewTypes',
            'apprenantKonosy_viewType',
            'apprenantKonosies_data',
            'apprenantKonosies_stats',
            'apprenantKonosies_total',
            'apprenantKonosies_filters',
            'apprenantKonosy_instance',
            'apprenantKonosy_title',
            'contextKey',
            'apprenantKonosies_permissions',
            'apprenantKonosies_permissionsByItem'
        );
    
        return [
            'apprenantKonosies_data' => $apprenantKonosies_data,
            'apprenantKonosies_stats' => $apprenantKonosies_stats,
            'apprenantKonosies_total' => $apprenantKonosies_total,
            'apprenantKonosies_filters' => $apprenantKonosies_filters,
            'apprenantKonosy_instance' => $apprenantKonosy_instance,
            'apprenantKonosy_viewType' => $apprenantKonosy_viewType,
            'apprenantKonosy_viewTypes' => $apprenantKonosy_viewTypes,
            'apprenantKonosy_partialViewName' => $apprenantKonosy_partialViewName,
            'contextKey' => $contextKey,
            'apprenantKonosy_compact_value' => $compact_value,
            'apprenantKonosies_permissions' => $apprenantKonosies_permissions,
            'apprenantKonosies_permissionsByItem' => $apprenantKonosies_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $apprenantKonosy_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $apprenantKonosy_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($apprenantKonosy_ids as $id) {
            $apprenantKonosy = $this->find($id);
            $this->authorize('update', $apprenantKonosy);
    
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
            'Nom'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(ApprenantKonosy $e, string $field): array
    {
        $meta = [
            'entity'         => 'apprenant_konosy',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
        ];

        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgApprenants\App\Requests\ApprenantKonosyRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }
       switch ($field) {
            case 'Nom':
                return $this->computeFieldMeta($e, $field, $meta, 'string', $validationRules);
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(ApprenantKonosy $e, array $changes): ApprenantKonosy
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
    public function formatDisplayValues(ApprenantKonosy $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'Nom':
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
