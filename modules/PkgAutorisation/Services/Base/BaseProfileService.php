<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\Services\Base;

use Modules\PkgAutorisation\Models\Profile;
use Modules\Core\Services\BaseService;

/**
 * Classe ProfileService pour gérer la persistance de l'entité Profile.
 */
class BaseProfileService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour profiles.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'user_id',
        'phone',
        'address',
        'profile_picture',
        'bio'
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
     * Constructeur de la classe ProfileService.
     */
    public function __construct()
    {
        parent::__construct(new Profile());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('profile');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('user_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutorisation::user.plural"), 'user_id', \Modules\PkgAutorisation\Models\User::class, 'name');
        }
    }

    /**
     * Crée une nouvelle instance de profile.
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
    public function getProfileStats(): array
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
