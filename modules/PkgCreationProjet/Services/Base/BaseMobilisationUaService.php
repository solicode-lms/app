<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationProjet\Models\MobilisationUa;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe MobilisationUaService pour gérer la persistance de l'entité MobilisationUa.
 */
class BaseMobilisationUaService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour mobilisationUas.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'unite_apprentissage_id',
        'bareme_evaluation_prototype',
        'criteres_evaluation_prototype',
        'bareme_evaluation_projet',
        'criteres_evaluation_projet',
        'description',
        'projet_id'
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
     * Constructeur de la classe MobilisationUaService.
     */
    public function __construct()
    {
        parent::__construct(new MobilisationUa());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationProjet::mobilisationUa.plural');
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
            $mobilisationUa = $this->find($data['id']);
            $mobilisationUa->fill($data);
        } else {
            $mobilisationUa = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($mobilisationUa->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $mobilisationUa->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($mobilisationUa->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($mobilisationUa->id, $data);
            }
        }

        return $mobilisationUa;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('mobilisationUa');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('unite_apprentissage_id', $scopeVariables)) {


                    $uniteApprentissageService = new \Modules\PkgCompetences\Services\UniteApprentissageService();
                    $uniteApprentissageIds = $this->getAvailableFilterValues('unite_apprentissage_id');
                    $uniteApprentissages = $uniteApprentissageService->getByIds($uniteApprentissageIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::uniteApprentissage.plural"), 
                        'unite_apprentissage_id', 
                        \Modules\PkgCompetences\Models\UniteApprentissage::class, 
                        'code',
                        $uniteApprentissages
                    );
                }
            
            
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
            



    }


    /**
     * Crée une nouvelle instance de mobilisationUa.
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
    public function getMobilisationUaStats(): array
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
            'table' => 'PkgCreationProjet::mobilisationUa._table',
            default => 'PkgCreationProjet::mobilisationUa._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('mobilisationUa_view_type', $default_view_type);
        $mobilisationUa_viewType = $this->viewState->get('mobilisationUa_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('mobilisationUa_view_type') === 'widgets') {
            $this->viewState->set("scope.mobilisationUa.visible", 1);
        }else{
            $this->viewState->remove("scope.mobilisationUa.visible");
        }
        
        // Récupération des données
        $mobilisationUas_data = $this->paginate($params);
        $mobilisationUas_stats = $this->getmobilisationUaStats();
        $mobilisationUas_total = $this->count();
        $mobilisationUas_filters = $this->getFieldsFilterable();
        $mobilisationUa_instance = $this->createInstance();
        $mobilisationUa_viewTypes = $this->getViewTypes();
        $mobilisationUa_partialViewName = $this->getPartialViewName($mobilisationUa_viewType);
        $mobilisationUa_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.mobilisationUa.stats', $mobilisationUas_stats);
    
        $mobilisationUas_permissions = [

            'edit-mobilisationUa' => Auth::user()->can('edit-mobilisationUa'),
            'destroy-mobilisationUa' => Auth::user()->can('destroy-mobilisationUa'),
            'show-mobilisationUa' => Auth::user()->can('show-mobilisationUa'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $mobilisationUas_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($mobilisationUas_data as $item) {
                $mobilisationUas_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'mobilisationUa_viewTypes',
            'mobilisationUa_viewType',
            'mobilisationUas_data',
            'mobilisationUas_stats',
            'mobilisationUas_total',
            'mobilisationUas_filters',
            'mobilisationUa_instance',
            'mobilisationUa_title',
            'contextKey',
            'mobilisationUas_permissions',
            'mobilisationUas_permissionsByItem'
        );
    
        return [
            'mobilisationUas_data' => $mobilisationUas_data,
            'mobilisationUas_stats' => $mobilisationUas_stats,
            'mobilisationUas_total' => $mobilisationUas_total,
            'mobilisationUas_filters' => $mobilisationUas_filters,
            'mobilisationUa_instance' => $mobilisationUa_instance,
            'mobilisationUa_viewType' => $mobilisationUa_viewType,
            'mobilisationUa_viewTypes' => $mobilisationUa_viewTypes,
            'mobilisationUa_partialViewName' => $mobilisationUa_partialViewName,
            'contextKey' => $contextKey,
            'mobilisationUa_compact_value' => $compact_value,
            'mobilisationUas_permissions' => $mobilisationUas_permissions,
            'mobilisationUas_permissionsByItem' => $mobilisationUas_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $mobilisationUa_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $mobilisationUa_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($mobilisationUa_ids as $id) {
            $mobilisationUa = $this->find($id);
            $this->authorize('update', $mobilisationUa);
    
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
            'unite_apprentissage_id'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(MobilisationUa $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCreationProjet\App\Requests\MobilisationUaRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'mobilisation_ua',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'unite_apprentissage_id':
                 $values = (new \Modules\PkgCompetences\Services\UniteApprentissageService())
                    ->getAllForSelect($e->uniteApprentissage)
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
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(MobilisationUa $e, array $changes): MobilisationUa
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
    public function formatDisplayValues(MobilisationUa $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'unite_apprentissage_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'uniteApprentissage'
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
