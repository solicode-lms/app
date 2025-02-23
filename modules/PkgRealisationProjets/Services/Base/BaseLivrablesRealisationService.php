<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Modules\PkgRealisationProjets\Models\LivrablesRealisation;
use Modules\Core\Services\BaseService;

/**
 * Classe LivrablesRealisationService pour gérer la persistance de l'entité LivrablesRealisation.
 */
class BaseLivrablesRealisationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour livrablesRealisations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'livrable_id',
        'lien',
        'titre',
        'description',
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
     * Constructeur de la classe LivrablesRealisationService.
     */
    public function __construct()
    {
        parent::__construct(new LivrablesRealisation());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('livrablesRealisation');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('livrable_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCreationProjet::livrable.plural"), 'livrable_id', \Modules\PkgCreationProjet\Models\Livrable::class, 'titre');
        }
        if (!array_key_exists('realisation_projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgRealisationProjets::realisationProjet.plural"), 'realisation_projet_id', \Modules\PkgRealisationProjets\Models\RealisationProjet::class, 'id');
        }
    }

    /**
     * Crée une nouvelle instance de livrablesRealisation.
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
    public function getLivrablesRealisationStats(): array
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
