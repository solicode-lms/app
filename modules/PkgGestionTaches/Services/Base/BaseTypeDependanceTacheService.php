<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Modules\PkgGestionTaches\Models\TypeDependanceTache;
use Modules\Core\Services\BaseService;

/**
 * Classe TypeDependanceTacheService pour gérer la persistance de l'entité TypeDependanceTache.
 */
class BaseTypeDependanceTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour typeDependanceTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'titre',
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
     * Constructeur de la classe TypeDependanceTacheService.
     */
    public function __construct()
    {
        parent::__construct(new TypeDependanceTache());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('typeDependanceTache');
        $this->fieldsFilterable = [];
    
    }

    /**
     * Crée une nouvelle instance de typeDependanceTache.
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
    public function getTypeDependanceTacheStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
