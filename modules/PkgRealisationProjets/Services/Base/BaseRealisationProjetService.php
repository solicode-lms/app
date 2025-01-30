<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationProjetService pour gérer la persistance de l'entité RealisationProjet.
 */
class BaseRealisationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'date_debut',
        'date_fin',
        'rapport',
        'projet_id',
        'etats_realisation_projet_id',
        'apprenant_id',
        'affectation_projet_id'
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
     * Constructeur de la classe RealisationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationProjet());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgCreationProjet::projet.plural"), 'projet_id', \Modules\PkgCreationProjet\Models\Projet::class, 'titre'),
            $this->generateManyToOneFilter(__("PkgRealisationProjets::etatsRealisationProjet.plural"), 'etats_realisation_projet_id', \Modules\PkgRealisationProjets\Models\EtatsRealisationProjet::class, 'titre'),
            $this->generateManyToOneFilter(__("PkgApprenants::apprenant.plural"), 'apprenant_id', \Modules\PkgApprenants\Models\Apprenant::class, 'nom'),
            $this->generateManyToOneFilter(__("PkgRealisationProjets::affectationProjet.plural"), 'affectation_projet_id', \Modules\PkgRealisationProjets\Models\AffectationProjet::class, 'id'),
        ];

    }

    /**
     * Crée une nouvelle instance de realisationProjet.
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
    public function getRealisationProjetStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }

}
