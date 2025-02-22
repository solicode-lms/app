<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Modules\PkgRealisationProjets\Models\Validation;
use Modules\Core\Services\BaseService;

/**
 * Classe ValidationService pour gérer la persistance de l'entité Validation.
 */
class BaseValidationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour validations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'transfert_competence_id',
        'note',
        'message',
        'is_valide',
        'realisation_projet_id'
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
     * Constructeur de la classe ValidationService.
     */
    public function __construct()
    {
        parent::__construct(new Validation());
        $this->fieldsFilterable = [];
    }

    public function initFieldsFilterable(){
       // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgCreationProjet::transfertCompetence.plural"), 'transfert_competence_id', \Modules\PkgCreationProjet\Models\TransfertCompetence::class, 'id'),
            $this->generateManyToOneFilter(__("PkgRealisationProjets::realisationProjet.plural"), 'realisation_projet_id', \Modules\PkgRealisationProjets\Models\RealisationProjet::class, 'id'),
        ];
    }

    /**
     * Crée une nouvelle instance de validation.
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
    public function getValidationStats(): array
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
