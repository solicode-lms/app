<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgCreationProjet\Models\Livrable;
use Modules\Core\Services\BaseService;

/**
 * Classe LivrableService pour gérer la persistance de l'entité Livrable.
 */
class BaseLivrableService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour livrables.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nature_livrable_id',
        'titre',
        'projet_id',
        'description',
        'is_affichable_seulement_par_formateur'
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
     * Constructeur de la classe LivrableService.
     */
    public function __construct()
    {
        parent::__construct(new Livrable());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationProjet::livrable.plural');
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
        $scopeVariables = $this->viewState->getScopeVariables('livrable');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('nature_livrable_id', $scopeVariables)) {


                    $natureLivrableService = new \Modules\PkgCreationProjet\Services\NatureLivrableService();
                    $natureLivrableIds = $this->getAvailableFilterValues('nature_livrable_id');
                    $natureLivrables = $natureLivrableService->getByIds($natureLivrableIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationProjet::natureLivrable.plural"), 
                        'nature_livrable_id', 
                        \Modules\PkgCreationProjet\Models\NatureLivrable::class, 
                        'nom',
                        $natureLivrables
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
     * Crée une nouvelle instance de livrable.
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
    public function getLivrableStats(): array
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
            'table' => 'PkgCreationProjet::livrable._table',
            default => 'PkgCreationProjet::livrable._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('livrable_view_type', $default_view_type);
        $livrable_viewType = $this->viewState->get('livrable_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('livrable_view_type') === 'widgets') {
            $this->viewState->set("scope.livrable.visible", 1);
        }else{
            $this->viewState->remove("scope.livrable.visible");
        }
        
        // Récupération des données
        $livrables_data = $this->paginate($params);
        $livrables_stats = $this->getlivrableStats();
        $livrables_total = $this->count();
        $livrables_filters = $this->getFieldsFilterable();
        $livrable_instance = $this->createInstance();
        $livrable_viewTypes = $this->getViewTypes();
        $livrable_partialViewName = $this->getPartialViewName($livrable_viewType);
        $livrable_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.livrable.stats', $livrables_stats);
    
        $livrables_permissions = [

            'edit-livrable' => Auth::user()->can('edit-livrable'),
            'destroy-livrable' => Auth::user()->can('destroy-livrable'),
            'show-livrable' => Auth::user()->can('show-livrable'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $livrables_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($livrables_data as $item) {
                $livrables_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'livrable_viewTypes',
            'livrable_viewType',
            'livrables_data',
            'livrables_stats',
            'livrables_total',
            'livrables_filters',
            'livrable_instance',
            'livrable_title',
            'contextKey',
            'livrables_permissions',
            'livrables_permissionsByItem'
        );
    
        return [
            'livrables_data' => $livrables_data,
            'livrables_stats' => $livrables_stats,
            'livrables_total' => $livrables_total,
            'livrables_filters' => $livrables_filters,
            'livrable_instance' => $livrable_instance,
            'livrable_viewType' => $livrable_viewType,
            'livrable_viewTypes' => $livrable_viewTypes,
            'livrable_partialViewName' => $livrable_partialViewName,
            'contextKey' => $contextKey,
            'livrable_compact_value' => $compact_value,
            'livrables_permissions' => $livrables_permissions,
            'livrables_permissionsByItem' => $livrables_permissionsByItem
        ];
    }

}
