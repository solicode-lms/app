<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

use Modules\PkgAutoformation\Models\RealisationChapitre;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationChapitreService pour gérer la persistance de l'entité RealisationChapitre.
 */
class BaseRealisationChapitreService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationChapitres.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'date_debut',
        'date_fin',
        'chapitre_id',
        'realisation_formation_id',
        'etat_chapitre_id'
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
     * Constructeur de la classe RealisationChapitreService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationChapitre());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationChapitre');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('chapitre_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::chapitre.plural"), 'chapitre_id', \Modules\PkgAutoformation\Models\Chapitre::class, 'nom');
        }
        if (!array_key_exists('realisation_formation_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::realisationFormation.plural"), 'realisation_formation_id', \Modules\PkgAutoformation\Models\RealisationFormation::class, 'id');
        }
        if (!array_key_exists('etat_chapitre_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::etatChapitre.plural"), 'etat_chapitre_id', \Modules\PkgAutoformation\Models\EtatChapitre::class, 'nom');
        }
    }

    /**
     * Crée une nouvelle instance de realisationChapitre.
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
    public function getRealisationChapitreStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
