<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\Core\Models\SysColor;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * Classe SysColorService pour gérer la persistance de l'entité SysColor.
 */
class BaseSysColorService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour sysColors.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'hex'
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
     * Constructeur de la classe SysColorService.
     */
    public function __construct()
    {
        parent::__construct(new SysColor());
        $this->fieldsFilterable = [];
        $this->title = __('Core::sysColor.plural');
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
            $sysColor = $this->find($data['id']);
            $sysColor->fill($data);
        } else {
            $sysColor = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($sysColor->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $sysColor->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($sysColor->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($sysColor->id, $data);
            }
        }

        return $sysColor;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('sysColor');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de sysColor.
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
    public function getSysColorStats(): array
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
            'table' => 'Core::sysColor._table',
            default => 'Core::sysColor._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('sysColor_view_type', $default_view_type);
        $sysColor_viewType = $this->viewState->get('sysColor_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('sysColor_view_type') === 'widgets') {
            $this->viewState->set("scope.sysColor.visible", 1);
        }else{
            $this->viewState->remove("scope.sysColor.visible");
        }
        
        // Récupération des données
        $sysColors_data = $this->paginate($params);
        $sysColors_stats = $this->getsysColorStats();
        $sysColors_total = $this->count();
        $sysColors_filters = $this->getFieldsFilterable();
        $sysColor_instance = $this->createInstance();
        $sysColor_viewTypes = $this->getViewTypes();
        $sysColor_partialViewName = $this->getPartialViewName($sysColor_viewType);
        $sysColor_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.sysColor.stats', $sysColors_stats);
    
        $sysColors_permissions = [

            'edit-sysColor' => Auth::user()->can('edit-sysColor'),
            'destroy-sysColor' => Auth::user()->can('destroy-sysColor'),
            'show-sysColor' => Auth::user()->can('show-sysColor'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $sysColors_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($sysColors_data as $item) {
                $sysColors_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'sysColor_viewTypes',
            'sysColor_viewType',
            'sysColors_data',
            'sysColors_stats',
            'sysColors_total',
            'sysColors_filters',
            'sysColor_instance',
            'sysColor_title',
            'contextKey',
            'sysColors_permissions',
            'sysColors_permissionsByItem'
        );
    
        return [
            'sysColors_data' => $sysColors_data,
            'sysColors_stats' => $sysColors_stats,
            'sysColors_total' => $sysColors_total,
            'sysColors_filters' => $sysColors_filters,
            'sysColor_instance' => $sysColor_instance,
            'sysColor_viewType' => $sysColor_viewType,
            'sysColor_viewTypes' => $sysColor_viewTypes,
            'sysColor_partialViewName' => $sysColor_partialViewName,
            'contextKey' => $contextKey,
            'sysColor_compact_value' => $compact_value,
            'sysColors_permissions' => $sysColors_permissions,
            'sysColors_permissionsByItem' => $sysColors_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $sysColor_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $sysColor_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($sysColor_ids as $id) {
            $sysColor = $this->find($id);
            $this->authorize('update', $sysColor);
    
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
            'name'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(SysColor $e, string $field): array
    {
        $meta = [
            'entity'         => 'sys_color',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
        ];

        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\Core\App\Requests\SysColorRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }
       switch ($field) {
            case 'name':
                return $this->computeFieldMeta($e, $field, $meta, 'string', $validationRules);
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(SysColor $e, array $changes): SysColor
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
    public function formatDisplayValues(SysColor $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'name':
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
