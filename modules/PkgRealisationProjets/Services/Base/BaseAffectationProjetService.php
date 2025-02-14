<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe AffectationProjetService pour gérer la persistance de l'entité AffectationProjet.
 */
class BaseAffectationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour affectationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'groupe_id',
        'date_debut',
        'date_fin',
        'projet_id',
        'description',
        'annee_formation_id'
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
     * Constructeur de la classe AffectationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new AffectationProjet());
        $this->fieldsFilterable = [];
    }

    public function initFieldsFilterable(){
       // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgApprenants::groupe.plural"), 'groupe_id', \Modules\PkgApprenants\Models\Groupe::class, 'code'),
            $this->generateManyToOneFilter(__("PkgCreationProjet::projet.plural"), 'projet_id', \Modules\PkgCreationProjet\Models\Projet::class, 'titre'),
        ];
    }

    /**
     * Crée une nouvelle instance de affectationProjet.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getAffectationProjetStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }

}
