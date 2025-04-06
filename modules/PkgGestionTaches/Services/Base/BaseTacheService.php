<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Modules\PkgGestionTaches\Models\Tache;
use Modules\Core\Services\BaseService;

/**
 * Classe TacheService pour gérer la persistance de l'entité Tache.
 */
class BaseTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour taches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'titre',
        'description',
        'dateDebut',
        'dateFin',
        'projet_id',
        'priorite_tache_id'
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
     * Constructeur de la classe TacheService.
     */
    public function __construct()
    {
        parent::__construct(new Tache());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('tache');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCreationProjet::projet.plural"), 'projet_id', \Modules\PkgCreationProjet\Models\Projet::class, 'titre');
        }
        if (!array_key_exists('priorite_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::prioriteTache.plural"), 'priorite_tache_id', \Modules\PkgGestionTaches\Models\PrioriteTache::class, 'nom');
        }
    }

    /**
     * Crée une nouvelle instance de tache.
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
    public function getTacheStats(): array
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
            'table' => 'PkgGestionTaches::tache._table',
            default => 'PkgGestionTaches::tache._table',
        };
    }

}
