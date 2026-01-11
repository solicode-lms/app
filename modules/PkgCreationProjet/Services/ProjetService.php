<?php

namespace Modules\PkgCreationProjet\Services;

use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationProjet\Services\Base\BaseProjetService;
use Modules\PkgSessions\Models\SessionFormation;
use Modules\Core\App\Exceptions\BlException;
use Modules\PkgCreationProjet\Services\Traits\Projet\ProjetActionsTrait;
use Modules\PkgCreationProjet\Services\Traits\Projet\ProjetCalculTrait;
use Modules\PkgCreationProjet\Services\Traits\Projet\ProjetRelationsTrait;

/**
 * Classe ProjetService pour gérer la persistance de l'entité Projet.
 */
class ProjetService extends BaseProjetService
{
    use ProjetActionsTrait, ProjetCalculTrait, ProjetRelationsTrait;

    protected array $index_with_relations = [
        'filiere',
        'formateur',
        'livrables',
        'resources',
        'taches',
        'affectationProjets',
        'affectationProjets.groupe'
    ];

    /**
     * Crée une instance de Projet.
     *
     * @param array $data Données initiales.
     * @return mixed L'instance créée.
     * @throws BlException Si l'ID du formateur ne peut pas être récupéré.
     */
    public function createInstance(array $data = [])
    {
        // Si l'utilisateur est formateur, on injecte son formateur_id
        if (Auth::check() && Auth::user()->hasRole('formateur')) {
            // Récupération sécurisée du formateur_id depuis la session
            $formateurId = $this->sessionState->get('formateur_id');

            if (!$formateurId) {
                throw new BlException("Impossible de récupérer l'identifiant du formateur depuis la session.");
            }

            $data['formateur_id'] = $formateurId;
        }

        return parent::createInstance($data);
    }

    /**
     * Crée un nouveau projet.
     * 
     * Cette méthode surcharge la méthode parente pour garantir que si l'utilisateur connecté
     * est un formateur, le projet lui est automatiquement assigné via son ID récupéré en session.
     *
     * @param array|object $data Données du projet.
     * @return mixed Le projet créé.
     * @throws \Exception Si l'ID du formateur ne peut pas être récupéré pour un formateur connecté.
     */
    public function create(array|object $data)
    {
        // Vérifier si l'utilisateur connecté est un formateur
        if (Auth::check() && Auth::user()->hasRole('formateur')) {
            // Récupération sécurisée du formateur_id depuis la session
            $formateurId = $this->sessionState->get('formateur_id');

            if (!$formateurId) {
                throw new \Exception("Impossible de récupérer l'identifiant du formateur depuis la session.");
            }

            // Forcer la valeur, peu importe ce qui est envoyé par le client
            if (is_array($data)) {
                $data['formateur_id'] = $formateurId;
            } elseif (is_object($data)) {
                $data->formateur_id = $formateurId;
            }
        }

        return parent::create($data);
    }

    /**
     * Vérifie les règles métier avant la suppression d'un projet.
     *
     * Empêche la suppression si le projet est déjà affecté à des groupes
     * pour garantir l'intégrité des données historiques.
     *
     * @param mixed $projet Le projet à supprimer.
     * @throws BlException Si le projet a des affectations actives.
     * @return void
     */
    public function beforeDeleteRules($projet)
    {
        // Vérification des affectations liées au projet
        $affectations = $projet->affectationProjets()->count();

        if ($affectations > 0) {
            throw new BlException("Impossible de supprimer ce projet : </br> il est encore affecté à un ou plusieurs groupes. </br> Supprimez d'abord les affectations avant de supprimer le projet.");
        }
    }


    /**
     * Vérifie les règles métier avant la mise à jour d'un projet.
     *
     * Interdit la modification de la session de formation une fois
     * que celle-ci a été définie lors de la création.
     *
     * @param array $projet Les données du projet à mettre à jour.
     * @throws BlException Si on tente de changer la session de formation.
     * @return void
     */
    public function beforeUpdateRules($projet)
    {
        // Empêcher la modification de la session de formation
        if (isset($projet['session_formation_id'])) {
            $original = $this->model->find($projet['id'] ?? null);
            if ($original && $original->session_formation_id != $projet['session_formation_id']) {
                throw new BlException('La session de formation ne peut pas être modifiée une fois le projet créé.');
            }
        }
    }

    /**
     * Exécute les actions nécessaires après la création d'un projet.
     *
     * Cette méthode orchestre l'initialisation du projet :
     * - Importation des compétences (mobilisations UA) depuis la session.
     * - Création automatique de l'arbre des tâches (Analyse, Tutos, Prototype, etc.).
     * - Ajout des livrables par défaut.
     *
     * @param mixed $projet Le projet fraîchement créé.
     * @return void
     */
    public function afterCreateRules($projet)
    {
        if (!$projet || !$projet->id) {
            return;
        }

        if ($projet->session_formation_id) {
            $session = SessionFormation::with([
                'alignementUas.uniteApprentissage.critereEvaluations.phaseEvaluation',
                'alignementUas.uniteApprentissage.chapitres'
            ])->find($projet->session_formation_id);

            if ($session) {
                // Utilisation des méthodes via ProjetRelationsTrait
                $this->updateMobilisationsUa($projet, $session);

                
                $this->addProjectTasks($projet, $session);
            }
        }

        // 🔹 Ajout des livrables par défaut via ProjetActionsTrait
        $this->addDefaultLivrables($projet);
    }

    /**
     * Point d'ancrage pour les règles métier après mise à jour.
     *
     * @param mixed $projet Le projet mis à jour.
     * @return void
     */
    public function afterUpdateRules($projet)
    {

    }

    /**
     * Définit l'ordre de tri par défaut pour les requêtes de projets.
     *
     * Trie les projets par la date de fin la plus récente de leurs affectations,
     * mettant en avant les projets actifs ou récemment terminés.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query La requête Eloquent.
     * @return \Illuminate\Database\Eloquent\Builder La requête triée.
     */
    public function defaultSort($query)
    {
        return $query
            ->withMax('affectationProjets', 'date_fin') // 🔥 Important
            ->orderBy('affectation_projets_max_date_fin', 'desc');
    }
}
