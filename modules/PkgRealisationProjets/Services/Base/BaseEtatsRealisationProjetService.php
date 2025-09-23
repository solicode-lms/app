<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe EtatsRealisationProjetService pour gérer la persistance de l'entité EtatsRealisationProjet.
 */
class BaseEtatsRealisationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatsRealisationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'titre',
        'code',
        'description',
        'sys_color_id',
        'is_editable_by_formateur'
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
     * Constructeur de la classe EtatsRealisationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new EtatsRealisationProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationProjets::etatsRealisationProjet.plural');
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
            $etatsRealisationProjet = $this->find($data['id']);
            $etatsRealisationProjet->fill($data);
        } else {
            $etatsRealisationProjet = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($etatsRealisationProjet->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $etatsRealisationProjet->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($etatsRealisationProjet->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($etatsRealisationProjet->id, $data);
            }
        }

        return $etatsRealisationProjet;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatsRealisationProjet');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de etatsRealisationProjet.
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
    public function getEtatsRealisationProjetStats(): array
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
            'table' => 'PkgRealisationProjets::etatsRealisationProjet._table',
            default => 'PkgRealisationProjets::etatsRealisationProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatsRealisationProjet_view_type', $default_view_type);
        $etatsRealisationProjet_viewType = $this->viewState->get('etatsRealisationProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatsRealisationProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.etatsRealisationProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.etatsRealisationProjet.visible");
        }
        
        // Récupération des données
        $etatsRealisationProjets_data = $this->paginate($params);
        $etatsRealisationProjets_stats = $this->getetatsRealisationProjetStats();
        $etatsRealisationProjets_total = $this->count();
        $etatsRealisationProjets_filters = $this->getFieldsFilterable();
        $etatsRealisationProjet_instance = $this->createInstance();
        $etatsRealisationProjet_viewTypes = $this->getViewTypes();
        $etatsRealisationProjet_partialViewName = $this->getPartialViewName($etatsRealisationProjet_viewType);
        $etatsRealisationProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatsRealisationProjet.stats', $etatsRealisationProjets_stats);
    
        $etatsRealisationProjets_permissions = [

            'edit-etatsRealisationProjet' => Auth::user()->can('edit-etatsRealisationProjet'),
            'destroy-etatsRealisationProjet' => Auth::user()->can('destroy-etatsRealisationProjet'),
            'show-etatsRealisationProjet' => Auth::user()->can('show-etatsRealisationProjet'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatsRealisationProjets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatsRealisationProjets_data as $item) {
                $etatsRealisationProjets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatsRealisationProjet_viewTypes',
            'etatsRealisationProjet_viewType',
            'etatsRealisationProjets_data',
            'etatsRealisationProjets_stats',
            'etatsRealisationProjets_total',
            'etatsRealisationProjets_filters',
            'etatsRealisationProjet_instance',
            'etatsRealisationProjet_title',
            'contextKey',
            'etatsRealisationProjets_permissions',
            'etatsRealisationProjets_permissionsByItem'
        );
    
        return [
            'etatsRealisationProjets_data' => $etatsRealisationProjets_data,
            'etatsRealisationProjets_stats' => $etatsRealisationProjets_stats,
            'etatsRealisationProjets_total' => $etatsRealisationProjets_total,
            'etatsRealisationProjets_filters' => $etatsRealisationProjets_filters,
            'etatsRealisationProjet_instance' => $etatsRealisationProjet_instance,
            'etatsRealisationProjet_viewType' => $etatsRealisationProjet_viewType,
            'etatsRealisationProjet_viewTypes' => $etatsRealisationProjet_viewTypes,
            'etatsRealisationProjet_partialViewName' => $etatsRealisationProjet_partialViewName,
            'contextKey' => $contextKey,
            'etatsRealisationProjet_compact_value' => $compact_value,
            'etatsRealisationProjets_permissions' => $etatsRealisationProjets_permissions,
            'etatsRealisationProjets_permissionsByItem' => $etatsRealisationProjets_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $etatsRealisationProjet_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $etatsRealisationProjet_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($etatsRealisationProjet_ids as $id) {
            $etatsRealisationProjet = $this->find($id);
            $this->authorize('update', $etatsRealisationProjet);
    
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
        return [
            'ordre',
            'titre',
            'description',
            'sys_color_id',
            'is_editable_by_formateur'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(EtatsRealisationProjet $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgRealisationProjets\App\Requests\EtatsRealisationProjetRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'etats_realisation_projet',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'ordre':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            case 'titre':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'description':
                return $this->computeFieldMeta($e, $field, $meta, 'text');

            case 'sys_color_id':
                 $values = (new \Modules\Core\Services\SysColorService())
                    ->getAllForSelect($e->sysColor)
                    ->map(fn($entity) => [
                        'value' => (int) $entity->id,
                        'label' => (string) $entity,
                    ])
                    ->toArray();

                return $this->computeFieldMeta($e, $field, $meta, 'select', [
                    'required' => true,
                    'options'  => [
                        'source' => 'static',
                        'values' => $values,
                    ],
                ]);
            case 'is_editable_by_formateur':
                return $this->computeFieldMeta($e, $field, $meta, 'boolean');

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(EtatsRealisationProjet $e, array $changes): EtatsRealisationProjet
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
    public function formatDisplayValues(EtatsRealisationProjet $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'ordre':
                    $html = view('Core::fields_by_type.integer', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'ordre'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'titre':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'description':
                    $html = view('Core::fields_by_type.text', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'sys_color_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'couleur',
                        'relationName' => 'sysColor'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'is_editable_by_formateur':
                    $html = view('Core::fields_by_type.boolean', [
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
