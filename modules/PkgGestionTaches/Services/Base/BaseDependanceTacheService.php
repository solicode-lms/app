<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Modules\PkgGestionTaches\Models\DependanceTache;
use Modules\Core\Services\BaseService;

/**
 * Classe DependanceTacheService pour gérer la persistance de l'entité DependanceTache.
 */
class BaseDependanceTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour dependanceTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'tache_id',
        'type_dependance_tache_id',
        'tache_cible_id'
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
     * Constructeur de la classe DependanceTacheService.
     */
    public function __construct()
    {
        parent::__construct(new DependanceTache());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('dependanceTache');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::tache.plural"), 'tache_id', \Modules\PkgGestionTaches\Models\Tache::class, 'titre');
        }
        if (!array_key_exists('type_dependance_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::typeDependanceTache.plural"), 'type_dependance_tache_id', \Modules\PkgGestionTaches\Models\TypeDependanceTache::class, 'titre');
        }
        if (!array_key_exists('tache_cible_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::tache.plural"), 'tache_cible_id', \Modules\PkgGestionTaches\Models\Tache::class, 'titre');
        }
    }

    /**
     * Crée une nouvelle instance de dependanceTache.
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
    public function getDependanceTacheStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
