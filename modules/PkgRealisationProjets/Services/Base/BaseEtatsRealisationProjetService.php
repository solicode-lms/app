<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatsRealisationProjetService pour gérer la persistance de l'entité EtatsRealisationProjet.
 */
class BaseEtatsRealisationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatsRealisationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'titre',
        'description',
        'formateur_id'
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
     * Constructeur de la classe EtatsRealisationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new EtatsRealisationProjet());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom'),
        ];

    }

    /**
     * Crée une nouvelle instance de etatsRealisationProjet.
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
    public function getEtatsRealisationProjetStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }

}
