<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Modules\PkgGestionTaches\Models\HistoriqueRealisationTache;
use Modules\Core\Services\BaseService;

/**
 * Classe HistoriqueRealisationTacheService pour gérer la persistance de l'entité HistoriqueRealisationTache.
 */
class BaseHistoriqueRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour historiqueRealisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'dateModification',
        'changement',
        'realisation_tache_id'
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
     * Constructeur de la classe HistoriqueRealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new HistoriqueRealisationTache());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('historiqueRealisationTache');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('realisation_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::realisationTache.plural"), 'realisation_tache_id', \Modules\PkgGestionTaches\Models\RealisationTache::class, 'id');
        }
    }

    /**
     * Crée une nouvelle instance de historiqueRealisationTache.
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
    public function getHistoriqueRealisationTacheStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
