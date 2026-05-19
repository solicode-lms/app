<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationProjet\Models\NatureLivrable;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe NatureLivrableService pour gérer la persistance de l'entité NatureLivrable.
 */
class BaseNatureLivrableService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour natureLivrables.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'description',
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
     * Constructeur de la classe NatureLivrableService.
     */
    public function __construct()
    {
        parent::__construct(new NatureLivrable());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationProjet::natureLivrable.plural');
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
            $natureLivrable = $this->find($data['id']);
            $natureLivrable->fill($data);
        } else {
            $natureLivrable = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($natureLivrable->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $natureLivrable->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($natureLivrable->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($natureLivrable->id, $data);
            }
        }

        return $natureLivrable;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('natureLivrable');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de natureLivrable.
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
    public function getNatureLivrableStats(): array
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
            'table' => 'PkgCreationProjet::natureLivrable._table',
            default => 'PkgCreationProjet::natureLivrable._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('natureLivrable_view_type', $default_view_type);
        $natureLivrable_viewType = $this->viewState->get('natureLivrable_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('natureLivrable_view_type') === 'widgets') {
            $this->viewState->set("scope.natureLivrable.visible", 1);
        }else{
            $this->viewState->remove("scope.natureLivrable.visible");
        }
        
        // Récupération des données
        $natureLivrables_data = $this->paginate($params);
        $natureLivrables_stats = $this->getnatureLivrableStats();
        $natureLivrables_total = $this->count();
        $natureLivrables_filters = $this->getFieldsFilterable();
        $natureLivrable_instance = $this->createInstance();
        $natureLivrable_viewTypes = $this->getViewTypes();
        $natureLivrable_partialViewName = $this->getPartialViewName($natureLivrable_viewType);
        $natureLivrable_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.natureLivrable.stats', $natureLivrables_stats);
    
        $natureLivrables_permissions = [

            'edit-natureLivrable' => Auth::user()->can('edit-natureLivrable'),
            'destroy-natureLivrable' => Auth::user()->can('destroy-natureLivrable'),
            'show-natureLivrable' => Auth::user()->can('show-natureLivrable'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $natureLivrables_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($natureLivrables_data as $item) {
                $natureLivrables_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'natureLivrable_viewTypes',
            'natureLivrable_viewType',
            'natureLivrables_data',
            'natureLivrables_stats',
            'natureLivrables_total',
            'natureLivrables_filters',
            'natureLivrable_instance',
            'natureLivrable_title',
            'contextKey',
            'natureLivrables_permissions',
            'natureLivrables_permissionsByItem'
        );
    
        return [
            'natureLivrables_data' => $natureLivrables_data,
            'natureLivrables_stats' => $natureLivrables_stats,
            'natureLivrables_total' => $natureLivrables_total,
            'natureLivrables_filters' => $natureLivrables_filters,
            'natureLivrable_instance' => $natureLivrable_instance,
            'natureLivrable_viewType' => $natureLivrable_viewType,
            'natureLivrable_viewTypes' => $natureLivrable_viewTypes,
            'natureLivrable_partialViewName' => $natureLivrable_partialViewName,
            'contextKey' => $contextKey,
            'natureLivrable_compact_value' => $compact_value,
            'natureLivrables_permissions' => $natureLivrables_permissions,
            'natureLivrables_permissionsByItem' => $natureLivrables_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $natureLivrable_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $natureLivrable_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($natureLivrable_ids as $id) {
            $natureLivrable = $this->find($id);
            $this->authorize('update', $natureLivrable);
    
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
            'nom'
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
    public function buildFieldMeta(NatureLivrable $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCreationProjet\App\Requests\NatureLivrableRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'nature_livrable',
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
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(NatureLivrable $e, array $changes): NatureLivrable
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
    public function formatDisplayValues(NatureLivrable $e, array $fields): array
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
