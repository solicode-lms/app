<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatRealisationChapitreService pour gérer la persistance de l'entité EtatRealisationChapitre.
 */
class BaseEtatRealisationChapitreService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatRealisationChapitres.
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
     * Constructeur de la classe EtatRealisationChapitreService.
     */
    public function __construct()
    {
        parent::__construct(new EtatRealisationChapitre());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::etatRealisationChapitre.plural');
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
            $etatRealisationChapitre = $this->find($data['id']);
            $etatRealisationChapitre->fill($data);
        } else {
            $etatRealisationChapitre = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($etatRealisationChapitre->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $etatRealisationChapitre->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($etatRealisationChapitre->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($etatRealisationChapitre->id, $data);
            }
        }

        return $etatRealisationChapitre;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatRealisationChapitre');
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
     * Crée une nouvelle instance de etatRealisationChapitre.
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
    public function getEtatRealisationChapitreStats(): array
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
            'table' => 'PkgApprentissage::etatRealisationChapitre._table',
            default => 'PkgApprentissage::etatRealisationChapitre._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatRealisationChapitre_view_type', $default_view_type);
        $etatRealisationChapitre_viewType = $this->viewState->get('etatRealisationChapitre_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatRealisationChapitre_view_type') === 'widgets') {
            $this->viewState->set("scope.etatRealisationChapitre.visible", 1);
        }else{
            $this->viewState->remove("scope.etatRealisationChapitre.visible");
        }
        
        // Récupération des données
        $etatRealisationChapitres_data = $this->paginate($params);
        $etatRealisationChapitres_stats = $this->getetatRealisationChapitreStats();
        $etatRealisationChapitres_total = $this->count();
        $etatRealisationChapitres_filters = $this->getFieldsFilterable();
        $etatRealisationChapitre_instance = $this->createInstance();
        $etatRealisationChapitre_viewTypes = $this->getViewTypes();
        $etatRealisationChapitre_partialViewName = $this->getPartialViewName($etatRealisationChapitre_viewType);
        $etatRealisationChapitre_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatRealisationChapitre.stats', $etatRealisationChapitres_stats);
    
        $etatRealisationChapitres_permissions = [

            'edit-etatRealisationChapitre' => Auth::user()->can('edit-etatRealisationChapitre'),
            'destroy-etatRealisationChapitre' => Auth::user()->can('destroy-etatRealisationChapitre'),
            'show-etatRealisationChapitre' => Auth::user()->can('show-etatRealisationChapitre'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatRealisationChapitres_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatRealisationChapitres_data as $item) {
                $etatRealisationChapitres_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatRealisationChapitre_viewTypes',
            'etatRealisationChapitre_viewType',
            'etatRealisationChapitres_data',
            'etatRealisationChapitres_stats',
            'etatRealisationChapitres_total',
            'etatRealisationChapitres_filters',
            'etatRealisationChapitre_instance',
            'etatRealisationChapitre_title',
            'contextKey',
            'etatRealisationChapitres_permissions',
            'etatRealisationChapitres_permissionsByItem'
        );
    
        return [
            'etatRealisationChapitres_data' => $etatRealisationChapitres_data,
            'etatRealisationChapitres_stats' => $etatRealisationChapitres_stats,
            'etatRealisationChapitres_total' => $etatRealisationChapitres_total,
            'etatRealisationChapitres_filters' => $etatRealisationChapitres_filters,
            'etatRealisationChapitre_instance' => $etatRealisationChapitre_instance,
            'etatRealisationChapitre_viewType' => $etatRealisationChapitre_viewType,
            'etatRealisationChapitre_viewTypes' => $etatRealisationChapitre_viewTypes,
            'etatRealisationChapitre_partialViewName' => $etatRealisationChapitre_partialViewName,
            'contextKey' => $contextKey,
            'etatRealisationChapitre_compact_value' => $compact_value,
            'etatRealisationChapitres_permissions' => $etatRealisationChapitres_permissions,
            'etatRealisationChapitres_permissionsByItem' => $etatRealisationChapitres_permissionsByItem
        ];
    }

}
