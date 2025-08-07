<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgRealisationTache\Models\CommentaireRealisationTache;
use Modules\Core\Services\BaseService;

/**
 * Classe CommentaireRealisationTacheService pour gérer la persistance de l'entité CommentaireRealisationTache.
 */
class BaseCommentaireRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour commentaireRealisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'commentaire',
        'dateCommentaire',
        'realisation_tache_id',
        'formateur_id',
        'apprenant_id'
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
     * Constructeur de la classe CommentaireRealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new CommentaireRealisationTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationTache::commentaireRealisationTache.plural');
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
            $commentaireRealisationTache = $this->find($data['id']);
            $commentaireRealisationTache->fill($data);
        } else {
            $commentaireRealisationTache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($commentaireRealisationTache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $commentaireRealisationTache->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($commentaireRealisationTache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($commentaireRealisationTache->id, $data);
            }
        }

        return $commentaireRealisationTache;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('commentaireRealisationTache');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('realisation_tache_id', $scopeVariables)) {


                    $realisationTacheService = new \Modules\PkgRealisationTache\Services\RealisationTacheService();
                    $realisationTacheIds = $this->getAvailableFilterValues('realisation_tache_id');
                    $realisationTaches = $realisationTacheService->getByIds($realisationTacheIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationTache::realisationTache.plural"), 
                        'realisation_tache_id', 
                        \Modules\PkgRealisationTache\Models\RealisationTache::class, 
                        'id',
                        $realisationTaches
                    );
                }
            
            
                if (!array_key_exists('formateur_id', $scopeVariables)) {


                    $formateurService = new \Modules\PkgFormation\Services\FormateurService();
                    $formateurIds = $this->getAvailableFilterValues('formateur_id');
                    $formateurs = $formateurService->getByIds($formateurIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgFormation::formateur.plural"), 
                        'formateur_id', 
                        \Modules\PkgFormation\Models\Formateur::class, 
                        'nom',
                        $formateurs
                    );
                }
            
            
                if (!array_key_exists('apprenant_id', $scopeVariables)) {


                    $apprenantService = new \Modules\PkgApprenants\Services\ApprenantService();
                    $apprenantIds = $this->getAvailableFilterValues('apprenant_id');
                    $apprenants = $apprenantService->getByIds($apprenantIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprenants::apprenant.plural"), 
                        'apprenant_id', 
                        \Modules\PkgApprenants\Models\Apprenant::class, 
                        'nom',
                        $apprenants
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de commentaireRealisationTache.
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
    public function getCommentaireRealisationTacheStats(): array
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
            'table' => 'PkgRealisationTache::commentaireRealisationTache._table',
            default => 'PkgRealisationTache::commentaireRealisationTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('commentaireRealisationTache_view_type', $default_view_type);
        $commentaireRealisationTache_viewType = $this->viewState->get('commentaireRealisationTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('commentaireRealisationTache_view_type') === 'widgets') {
            $this->viewState->set("scope.commentaireRealisationTache.visible", 1);
        }else{
            $this->viewState->remove("scope.commentaireRealisationTache.visible");
        }
        
        // Récupération des données
        $commentaireRealisationTaches_data = $this->paginate($params);
        $commentaireRealisationTaches_stats = $this->getcommentaireRealisationTacheStats();
        $commentaireRealisationTaches_total = $this->count();
        $commentaireRealisationTaches_filters = $this->getFieldsFilterable();
        $commentaireRealisationTache_instance = $this->createInstance();
        $commentaireRealisationTache_viewTypes = $this->getViewTypes();
        $commentaireRealisationTache_partialViewName = $this->getPartialViewName($commentaireRealisationTache_viewType);
        $commentaireRealisationTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.commentaireRealisationTache.stats', $commentaireRealisationTaches_stats);
    
        $commentaireRealisationTaches_permissions = [

            'edit-commentaireRealisationTache' => Auth::user()->can('edit-commentaireRealisationTache'),
            'destroy-commentaireRealisationTache' => Auth::user()->can('destroy-commentaireRealisationTache'),
            'show-commentaireRealisationTache' => Auth::user()->can('show-commentaireRealisationTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $commentaireRealisationTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($commentaireRealisationTaches_data as $item) {
                $commentaireRealisationTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'commentaireRealisationTache_viewTypes',
            'commentaireRealisationTache_viewType',
            'commentaireRealisationTaches_data',
            'commentaireRealisationTaches_stats',
            'commentaireRealisationTaches_total',
            'commentaireRealisationTaches_filters',
            'commentaireRealisationTache_instance',
            'commentaireRealisationTache_title',
            'contextKey',
            'commentaireRealisationTaches_permissions',
            'commentaireRealisationTaches_permissionsByItem'
        );
    
        return [
            'commentaireRealisationTaches_data' => $commentaireRealisationTaches_data,
            'commentaireRealisationTaches_stats' => $commentaireRealisationTaches_stats,
            'commentaireRealisationTaches_total' => $commentaireRealisationTaches_total,
            'commentaireRealisationTaches_filters' => $commentaireRealisationTaches_filters,
            'commentaireRealisationTache_instance' => $commentaireRealisationTache_instance,
            'commentaireRealisationTache_viewType' => $commentaireRealisationTache_viewType,
            'commentaireRealisationTache_viewTypes' => $commentaireRealisationTache_viewTypes,
            'commentaireRealisationTache_partialViewName' => $commentaireRealisationTache_partialViewName,
            'contextKey' => $contextKey,
            'commentaireRealisationTache_compact_value' => $compact_value,
            'commentaireRealisationTaches_permissions' => $commentaireRealisationTaches_permissions,
            'commentaireRealisationTaches_permissionsByItem' => $commentaireRealisationTaches_permissionsByItem
        ];
    }

}
