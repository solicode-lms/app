<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Modules\PkgGestionTaches\Models\CommentaireRealisationTache;
use Modules\Core\Services\BaseService;

/**
 * Classe CommentaireRealisationTacheService pour gérer la persistance de l'entité CommentaireRealisationTache.
 */
class BaseCommentaireRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour commentaireRealisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'commentaire',
        'dateCommentaire',
        'realisation_tache_id',
        'formateur_id',
        'apprenant_id'
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
     * Constructeur de la classe CommentaireRealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new CommentaireRealisationTache());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('commentaireRealisationTache');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('realisation_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::realisationTache.plural"), 'realisation_tache_id', \Modules\PkgGestionTaches\Models\RealisationTache::class, 'id');
        }
        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }
        if (!array_key_exists('apprenant_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgApprenants::apprenant.plural"), 'apprenant_id', \Modules\PkgApprenants\Models\Apprenant::class, 'nom');
        }
    }

    /**
     * Crée une nouvelle instance de commentaireRealisationTache.
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
    public function getCommentaireRealisationTacheStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
