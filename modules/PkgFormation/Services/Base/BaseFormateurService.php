<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Services\Base;

use Modules\PkgFormation\Models\Formateur;
use Modules\Core\Services\BaseService;

/**
 * Classe FormateurService pour gérer la persistance de l'entité Formateur.
 */
class BaseFormateurService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour formateurs.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'matricule',
        'nom',
        'prenom',
        'prenom_arab',
        'nom_arab',
        'email',
        'tele_num',
        'adresse',
        'diplome',
        'echelle',
        'echelon',
        'profile_image',
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
     * Constructeur de la classe FormateurService.
     */
    public function __construct()
    {
        parent::__construct(new Formateur());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('formateur');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('specialites', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToManyFilter(__("PkgFormation::specialite.plural"), 'specialite_id', \Modules\PkgFormation\Models\Specialite::class, 'nom');
        }
        if (!array_key_exists('groupes', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToManyFilter(__("PkgApprenants::groupe.plural"), 'groupe_id', \Modules\PkgApprenants\Models\Groupe::class, 'code');
        }
    }

    /**
     * Crée une nouvelle instance de formateur.
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
    public function getFormateurStats(): array
    {

        $stats = $this->initStats();

        
            $relationStatSpecialite = parent::getStatsByRelation(
                \Modules\PkgFormation\Models\Specialite::class,
                'formateurs',
                'nom'
            );
            $stats = array_merge($stats, $relationStatSpecialite);

        return $stats;
    }


    public function initPassword(int $formateurId)
    {
        $formateur = $this->find($formateurId);
        if (!$formateur) {
            return false; 
        }
        $value =  $formateur->save();
        $this->pushServiceMessage("info","Traitement title", "message : résultat de traitement");
        return $value;
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
            'table' => 'PkgFormation::formateur._table',
            default => 'PkgFormation::formateur._table',
        };
    }

}
