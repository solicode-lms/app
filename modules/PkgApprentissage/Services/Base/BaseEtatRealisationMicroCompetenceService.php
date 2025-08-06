<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatRealisationMicroCompetenceService pour gérer la persistance de l'entité EtatRealisationMicroCompetence.
 */
class BaseEtatRealisationMicroCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatRealisationMicroCompetences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'nom',
        'code',
        'sys_color_id',
        'is_editable_only_by_formateur',
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
     * Constructeur de la classe EtatRealisationMicroCompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new EtatRealisationMicroCompetence());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::etatRealisationMicroCompetence.plural');
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
            $realisationTache = $this->find($data['id']);
            $realisationTache->fill($data);
        } else {
            $realisationTache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($realisationTache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $realisationTache->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($realisationTache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($realisationTache->id, $data);
            }
        }

        return $realisationTache;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatRealisationMicroCompetence');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('sys_color_id', $scopeVariables)) {


                    $sysColorService = new \Modules\Core\Services\SysColorService();
                    $sysColorIds = $this->getAvailableFilterValues('sys_color_id');
                    $sysColors = $sysColorService->getByIds($sysColorIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("Core::sysColor.plural"), 
                        'sys_color_id', 
                        \Modules\Core\Models\SysColor::class, 
                        'name',
                        $sysColors
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de etatRealisationMicroCompetence.
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
    public function getEtatRealisationMicroCompetenceStats(): array
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
            'table' => 'PkgApprentissage::etatRealisationMicroCompetence._table',
            default => 'PkgApprentissage::etatRealisationMicroCompetence._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatRealisationMicroCompetence_view_type', $default_view_type);
        $etatRealisationMicroCompetence_viewType = $this->viewState->get('etatRealisationMicroCompetence_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatRealisationMicroCompetence_view_type') === 'widgets') {
            $this->viewState->set("scope.etatRealisationMicroCompetence.visible", 1);
        }else{
            $this->viewState->remove("scope.etatRealisationMicroCompetence.visible");
        }
        
        // Récupération des données
        $etatRealisationMicroCompetences_data = $this->paginate($params);
        $etatRealisationMicroCompetences_stats = $this->getetatRealisationMicroCompetenceStats();
        $etatRealisationMicroCompetences_total = $this->count();
        $etatRealisationMicroCompetences_filters = $this->getFieldsFilterable();
        $etatRealisationMicroCompetence_instance = $this->createInstance();
        $etatRealisationMicroCompetence_viewTypes = $this->getViewTypes();
        $etatRealisationMicroCompetence_partialViewName = $this->getPartialViewName($etatRealisationMicroCompetence_viewType);
        $etatRealisationMicroCompetence_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatRealisationMicroCompetence.stats', $etatRealisationMicroCompetences_stats);
    
        $etatRealisationMicroCompetences_permissions = [

            'edit-etatRealisationMicroCompetence' => Auth::user()->can('edit-etatRealisationMicroCompetence'),
            'destroy-etatRealisationMicroCompetence' => Auth::user()->can('destroy-etatRealisationMicroCompetence'),
            'show-etatRealisationMicroCompetence' => Auth::user()->can('show-etatRealisationMicroCompetence'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatRealisationMicroCompetences_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatRealisationMicroCompetences_data as $item) {
                $etatRealisationMicroCompetences_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatRealisationMicroCompetence_viewTypes',
            'etatRealisationMicroCompetence_viewType',
            'etatRealisationMicroCompetences_data',
            'etatRealisationMicroCompetences_stats',
            'etatRealisationMicroCompetences_total',
            'etatRealisationMicroCompetences_filters',
            'etatRealisationMicroCompetence_instance',
            'etatRealisationMicroCompetence_title',
            'contextKey',
            'etatRealisationMicroCompetences_permissions',
            'etatRealisationMicroCompetences_permissionsByItem'
        );
    
        return [
            'etatRealisationMicroCompetences_data' => $etatRealisationMicroCompetences_data,
            'etatRealisationMicroCompetences_stats' => $etatRealisationMicroCompetences_stats,
            'etatRealisationMicroCompetences_total' => $etatRealisationMicroCompetences_total,
            'etatRealisationMicroCompetences_filters' => $etatRealisationMicroCompetences_filters,
            'etatRealisationMicroCompetence_instance' => $etatRealisationMicroCompetence_instance,
            'etatRealisationMicroCompetence_viewType' => $etatRealisationMicroCompetence_viewType,
            'etatRealisationMicroCompetence_viewTypes' => $etatRealisationMicroCompetence_viewTypes,
            'etatRealisationMicroCompetence_partialViewName' => $etatRealisationMicroCompetence_partialViewName,
            'contextKey' => $contextKey,
            'etatRealisationMicroCompetence_compact_value' => $compact_value,
            'etatRealisationMicroCompetences_permissions' => $etatRealisationMicroCompetences_permissions,
            'etatRealisationMicroCompetences_permissionsByItem' => $etatRealisationMicroCompetences_permissionsByItem
        ];
    }

}
