<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\EPackage;
use Modules\Core\Services\BaseService;

/**
 * Classe EPackageService pour gérer la persistance de l'entité EPackage.
 */
class BaseEPackageService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour ePackages.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
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
     * Constructeur de la classe EPackageService.
     */
    public function __construct()
    {
        parent::__construct(new EPackage());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('ePackage');
        $this->fieldsFilterable = [];
    
    }

    /**
     * Crée une nouvelle instance de ePackage.
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
    public function getEPackageStats(): array
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
            'table' => 'PkgGapp::ePackage._table',
            default => 'PkgGapp::ePackage._table',
        };
    }

}
