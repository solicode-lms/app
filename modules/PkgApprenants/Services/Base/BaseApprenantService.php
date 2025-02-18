<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Modules\PkgApprenants\Models\Apprenant;
use Modules\Core\Services\BaseService;

/**
 * Classe ApprenantService pour gérer la persistance de l'entité Apprenant.
 */
class BaseApprenantService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour apprenants.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'nom_arab',
        'prenom',
        'prenom_arab',
        'profile_image',
        'sexe',
        'tele_num',
        'diplome',
        'date_naissance',
        'lieu_naissance',
        'cin',
        'adresse',
        'niveaux_scolaire_id',
        'matricule',
        'nationalite_id',
        'actif',
        'date_inscription',
        'user_id'
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
     * Constructeur de la classe ApprenantService.
     */
    public function __construct()
    {
        parent::__construct(new Apprenant());
        $this->fieldsFilterable = [];
    }

    public function initFieldsFilterable(){
       // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgApprenants::niveauxScolaire.plural"), 'niveaux_scolaire_id', \Modules\PkgApprenants\Models\NiveauxScolaire::class, 'code'),
            $this->generateManyToManyFilter(__("PkgApprenants::groupe.plural"), 'groupe_id', \Modules\PkgApprenants\Models\Groupe::class, 'code'),
        ];
    }

    /**
     * Crée une nouvelle instance de apprenant.
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
    public function getApprenantStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }


    public function initPassword(int $apprenantId)
    {
        $apprenant = $this->find($apprenantId);
        if (!$apprenant) {
            return false; 
        }
        $value =  $apprenant->save();
        $this->pushServiceMessage("info","Traitement title", "message : résultat de traitement");
        return $value;
    }
    
}
