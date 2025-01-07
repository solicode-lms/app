<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Services\Base;

use Modules\PkgUtilisateurs\Models\Nationalite;
use Modules\Core\Services\BaseService;

/**
 * Classe NationaliteService pour gérer la persistance de l'entité Nationalite.
 */
class BaseNationaliteService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour nationalites.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'nom',
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
     * Constructeur de la classe NationaliteService.
     */
    public function __construct()
    {
        parent::__construct(new Nationalite());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de nationalite.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        $nationalite = parent::create([
            'code' => $data['code'],
            'nom' => $data['nom'],
            'description' => $data['description'],
        ]);

        return $nationalite;
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getNationaliteStats(): array
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
