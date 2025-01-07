<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\Appreciation;
use Modules\Core\Services\BaseService;

/**
 * Classe AppreciationService pour gérer la persistance de l'entité Appreciation.
 */
class BaseAppreciationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour appreciations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'description',
        'noteMin',
        'noteMax',
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
     * Constructeur de la classe AppreciationService.
     */
    public function __construct()
    {
        parent::__construct(new Appreciation());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgUtilisateurs::formateur.plural"), 'formateur_id', \Modules\PkgUtilisateurs\Models\Formateur::class, 'nom'),
        ];

    }

    /**
     * Crée une nouvelle instance de appreciation.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $appreciation = parent::create([
            'nom' => $data['nom'],
            'description' => $data['description'],
            'noteMin' => $data['noteMin'],
            'noteMax' => $data['noteMax'],
            'formateur_id' => $data['formateur_id'],
        ]);

        return $appreciation;
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getAppreciationStats(): array
    {

        $stats = [];

        // Ajouter les statistiques du propriétaire
        $contexteState = $this->getContextState();
        if ($contexteState !== null) {
            $stats[] = $contexteState;
        }
        

        return $stats;
    }

    public function getContextState()
    {
        if(!$this->contextState->isContextStateEnable()) return null; 
        $value = $this->contextState->getTitle();
        return [
                "icon" => "fas fa-filter",
                "label" => "Filtre",
                "value" =>  $value
        ];
    }
}
