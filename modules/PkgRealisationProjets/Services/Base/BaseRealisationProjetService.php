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
        'affectation_projet_id',
        'apprenant_id',
        'etats_realisation_projet_id',
        'date_debut',
        'date_fin',
        'rapport'
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
        $this->fieldsFilterable = [];
    }

    public function initFieldsFilterable(){
       // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgRealisationProjets::affectationProjet.plural"), 'affectation_projet_id', \Modules\PkgRealisationProjets\Models\AffectationProjet::class, 'id'),
            $this->generateManyToOneFilter(__("PkgApprenants::apprenant.plural"), 'apprenant_id', \Modules\PkgApprenants\Models\Apprenant::class, 'nom'),
            $this->generateManyToOneFilter(__("PkgRealisationProjets::etatsRealisationProjet.plural"), 'etats_realisation_projet_id', \Modules\PkgRealisationProjets\Models\EtatsRealisationProjet::class, 'titre'),
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

        // Ajouter les statistiques du propriétaire
        $contexteState = $this->getContextState();
        if ($contexteState !== null) {
            $stats[] = $contexteState;
        }
        

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
