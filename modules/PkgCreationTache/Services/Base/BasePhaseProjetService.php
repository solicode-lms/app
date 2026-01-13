<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationTache\Models\PhaseProjet;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe PhaseProjetService pour gérer la persistance de l'entité PhaseProjet.
 */
class BasePhaseProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour phaseProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'nom',
        'description'
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
     * Constructeur de la classe PhaseProjetService.
     */
    public function __construct()
    {
        parent::__construct(new PhaseProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationTache::phaseProjet.plural');
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
            $phaseProjet = $this->find($data['id']);
            $phaseProjet->fill($data);
        } else {
            $phaseProjet = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($phaseProjet->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $phaseProjet->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($phaseProjet->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($phaseProjet->id, $data);
            }
        }

        return $phaseProjet;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('phaseProjet');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de phaseProjet.
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
    public function getPhaseProjetStats(): array
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
            'table' => 'PkgCreationTache::phaseProjet._table',
            default => 'PkgCreationTache::phaseProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('phaseProjet_view_type', $default_view_type);
        $phaseProjet_viewType = $this->viewState->get('phaseProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('phaseProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.phaseProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.phaseProjet.visible");
        }
        
        // Récupération des données
        $phaseProjets_data = $this->paginate($params);
        $phaseProjets_stats = $this->getphaseProjetStats();
        $phaseProjets_total = $this->count();
        $phaseProjets_filters = $this->getFieldsFilterable();
        $phaseProjet_instance = $this->createInstance();
        $phaseProjet_viewTypes = $this->getViewTypes();
        $phaseProjet_partialViewName = $this->getPartialViewName($phaseProjet_viewType);
        $phaseProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.phaseProjet.stats', $phaseProjets_stats);
    
        $phaseProjets_permissions = [

            'edit-phaseProjet' => Auth::user()->can('edit-phaseProjet'),
            'destroy-phaseProjet' => Auth::user()->can('destroy-phaseProjet'),
            'show-phaseProjet' => Auth::user()->can('show-phaseProjet'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $phaseProjets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($phaseProjets_data as $item) {
                $phaseProjets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'phaseProjet_viewTypes',
            'phaseProjet_viewType',
            'phaseProjets_data',
            'phaseProjets_stats',
            'phaseProjets_total',
            'phaseProjets_filters',
            'phaseProjet_instance',
            'phaseProjet_title',
            'contextKey',
            'phaseProjets_permissions',
            'phaseProjets_permissionsByItem'
        );
    
        return [
            'phaseProjets_data' => $phaseProjets_data,
            'phaseProjets_stats' => $phaseProjets_stats,
            'phaseProjets_total' => $phaseProjets_total,
            'phaseProjets_filters' => $phaseProjets_filters,
            'phaseProjet_instance' => $phaseProjet_instance,
            'phaseProjet_viewType' => $phaseProjet_viewType,
            'phaseProjet_viewTypes' => $phaseProjet_viewTypes,
            'phaseProjet_partialViewName' => $phaseProjet_partialViewName,
            'contextKey' => $contextKey,
            'phaseProjet_compact_value' => $compact_value,
            'phaseProjets_permissions' => $phaseProjets_permissions,
            'phaseProjets_permissionsByItem' => $phaseProjets_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $phaseProjet_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $phaseProjet_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($phaseProjet_ids as $id) {
            $phaseProjet = $this->find($id);
            $this->authorize('update', $phaseProjet);
    
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
            'ordre',
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
    public function buildFieldMeta(PhaseProjet $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCreationTache\App\Requests\PhaseProjetRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'phase_projet',
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

            case 'nom':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(PhaseProjet $e, array $changes): PhaseProjet
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
    public function formatDisplayValues(PhaseProjet $e, array $fields): array
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
