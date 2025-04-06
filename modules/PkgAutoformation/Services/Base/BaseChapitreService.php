<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

use Modules\PkgAutoformation\Models\Chapitre;
use Modules\Core\Services\BaseService;

/**
 * Classe ChapitreService pour gérer la persistance de l'entité Chapitre.
 */
class BaseChapitreService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour chapitres.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'lien',
        'coefficient',
        'description',
        'ordre',
        'is_officiel',
        'formation_id',
        'niveau_competence_id',
        'formateur_id',
        'chapitre_officiel_id'
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
     * Constructeur de la classe ChapitreService.
     */
    public function __construct()
    {
        parent::__construct(new Chapitre());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('chapitre');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('formation_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::formation.plural"), 'formation_id', \Modules\PkgAutoformation\Models\Formation::class, 'nom');
        }
        if (!array_key_exists('niveau_competence_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCompetences::niveauCompetence.plural"), 'niveau_competence_id', \Modules\PkgCompetences\Models\NiveauCompetence::class, 'nom');
        }
        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }
        if (!array_key_exists('chapitre_officiel_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::chapitre.plural"), 'chapitre_officiel_id', \Modules\PkgAutoformation\Models\Chapitre::class, 'nom');
        }
    }

    /**
     * Crée une nouvelle instance de chapitre.
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
    public function getChapitreStats(): array
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
            'table' => 'PkgAutoformation::chapitre._table',
            default => 'PkgAutoformation::chapitre._table',
        };
    }

}
