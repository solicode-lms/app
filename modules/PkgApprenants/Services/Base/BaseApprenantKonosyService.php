<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Modules\PkgApprenants\Models\ApprenantKonosy;
use Modules\Core\Services\BaseService;

/**
 * Classe ApprenantKonosyService pour gérer la persistance de l'entité ApprenantKonosy.
 */
class BaseApprenantKonosyService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour apprenantKonosies.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'MatriculeEtudiant',
        'Nom',
        'Prenom',
        'Sexe',
        'EtudiantActif',
        'Diplome',
        'Principale',
        'LibelleLong',
        'CodeDiplome',
        'DateNaissance',
        'DateInscription',
        'LieuNaissance',
        'CIN',
        'NTelephone',
        'Adresse',
        'Nationalite',
        'Nom_Arabe',
        'Prenom_Arabe',
        'NiveauScolaire'
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
     * Constructeur de la classe ApprenantKonosyService.
     */
    public function __construct()
    {
        parent::__construct(new ApprenantKonosy());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('apprenantKonosy');
        $this->fieldsFilterable = [];
    
    }

    /**
     * Crée une nouvelle instance de apprenantKonosy.
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
    public function getApprenantKonosyStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }




    /**
     * Retourne les types de vues disponibles pour l'index (ex: table, widgets...)
     */
    public function getViewTypes(): array
    {
        return [
            [
                'type'  => 'table',
                'label' => 'Vue Tableau',
                'icon'  => 'fa-table',
            ],
        ];
    }

    /**
     * Retourne le nom de la vue partielle selon le type de vue sélectionné
     */
    public function getPartialViewName(string $viewType): string
    {
        return match ($viewType) {
            'table' => 'PkgApprenants::apprenantKonosy._table',
            default => 'PkgApprenants::apprenantKonosy._table',
        };
    }

}
