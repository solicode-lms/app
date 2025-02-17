<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Modules\PkgCreationProjet\Models\Livrable;
use Modules\Core\Services\BaseService;

/**
 * Classe LivrableService pour gérer la persistance de l'entité Livrable.
 */
class BaseLivrableService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour livrables.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nature_livrable_id',
        'titre',
        'projet_id',
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
     * Constructeur de la classe LivrableService.
     */
    public function __construct()
    {
        parent::__construct(new Livrable());
        $this->fieldsFilterable = [];
    }

    public function initFieldsFilterable(){
       // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgCreationProjet::natureLivrable.plural"), 'nature_livrable_id', \Modules\PkgCreationProjet\Models\NatureLivrable::class, 'nom'),
            $this->generateManyToOneFilter(__("PkgCreationProjet::projet.plural"), 'projet_id', \Modules\PkgCreationProjet\Models\Projet::class, 'titre'),
        ];
    }

    /**
     * Crée une nouvelle instance de livrable.
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
    public function getLivrableStats(): array
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
