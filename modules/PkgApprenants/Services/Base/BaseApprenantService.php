<?php
// add nombre_realisation_taches_en_cours to fieldsSearchable



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
        'cin',
        'date_naissance',
        'sexe',
        'nationalite_id',
        'lieu_naissance',
        'diplome',
        'adresse',
        'niveaux_scolaire_id',
        'tele_num',
        'user_id',
        'matricule',
        'date_inscription',
        'actif',
        'nombre_realisation_taches_en_cours'
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


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('apprenant');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('niveaux_scolaire_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgApprenants::niveauxScolaire.plural"), 'niveaux_scolaire_id', \Modules\PkgApprenants\Models\NiveauxScolaire::class, 'code');
        }
        if (!array_key_exists('groupes', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToManyFilter(__("PkgApprenants::groupe.plural"), 'groupe_id', \Modules\PkgApprenants\Models\Groupe::class, 'code');
        }
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
