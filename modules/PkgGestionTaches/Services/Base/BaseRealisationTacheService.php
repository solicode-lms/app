<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Modules\PkgGestionTaches\Models\RealisationTache;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationTacheService pour gérer la persistance de l'entité RealisationTache.
 */
class BaseRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'Livrables',
        'tache_id',
        'realisation_projet_id',
        'dateDebut',
        'dateFin',
        'etat_realisation_tache_id',
        'remarques_formateur',
        'remarques_apprenant'
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
     * Constructeur de la classe RealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationTache());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationTache');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::tache.plural"), 'tache_id', \Modules\PkgGestionTaches\Models\Tache::class, 'titre');
        }
        if (!array_key_exists('realisation_projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgRealisationProjets::realisationProjet.plural"), 'realisation_projet_id', \Modules\PkgRealisationProjets\Models\RealisationProjet::class, 'id');
        }
        if (!array_key_exists('etat_realisation_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::etatRealisationTache.plural"), 'etat_realisation_tache_id', \Modules\PkgGestionTaches\Models\EtatRealisationTache::class, 'nom');
        }
    }

    /**
     * Crée une nouvelle instance de realisationTache.
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
    public function getRealisationTacheStats(): array
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


}
