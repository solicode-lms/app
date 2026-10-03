<?php

namespace Modules\PkgRealisationProjets\Services\Traits\AffectationProjet;

use Modules\Core\App\Exceptions\BlException;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService;

/**
 * Trait AffectationProjetCrudTrait
 * 
 * Gestion des Hooks CRUD (before/after create/update/delete) et création d'instances.
 */
trait AffectationProjetCrudTrait
{
    /**
     * Crée une nouvelle instance de l'entité AffectationProjet.
     * 
     * Initialise automatiquement les dates de début et de fin à partir 
     * de la session de formation liée au projet, et ajoute les remarques
     * sur les jours fériés si disponibles.
     *
     * @param array $data Données initiales.
     * @return mixed L'instance pré-remplie.
     */
    public function createInstance(array $data = [])
    {
        // Récupérer l'instance par défaut via la classe parent
        $instance = parent::createInstance($data);

        // Si le projet est défini, récupérer la session de formation
        if (!empty($instance->projet_id)) {
            $projet = \Modules\PkgCreationProjet\Models\Projet::with('sessionFormation')
                ->find($instance->projet_id);

            if ($projet && $projet->sessionFormation) {
                // Initialiser date_debut si non fourni
                if (empty($instance->date_debut)) {
                    $instance->date_debut = $projet->sessionFormation->date_debut;
                }

                // Initialiser date_fin si non fourni
                if (empty($instance->date_fin)) {
                    $instance->date_fin = $projet->sessionFormation->date_fin;
                }

                // Initialiser Remarques
                if (!empty($projet->sessionFormation->jour_feries_vacances)) {
                    $instance->description .= "<p>Jours fériés et vacances : " . $projet->sessionFormation->jour_feries_vacances . " </p>";
                }
            }
        }

        // Si la date de début est toujours vide, on met la date actuelle
        if (empty($instance->date_debut)) {
            $dateDebut = \Carbon\Carbon::now();
        } else {
            $dateDebut = \Carbon\Carbon::parse($instance->date_debut);
        }
        // Fixer l'heure de début à 09:00:00
        $dateDebut->setTime(9, 0, 0);
        $instance->date_debut = $dateDebut->format('Y-m-d H:i:s');

        // Si la date de fin est toujours vide, on calcule +15 jours puis on cherche le dernier vendredi à 17h
        if (empty($instance->date_fin)) {
            $dateFin = \Carbon\Carbon::parse($instance->date_debut)->addDays(15);
            
            // On recule au vendredi précédent si ce n'est pas déjà un vendredi
            if (!$dateFin->isFriday()) {
                $dateFin->previous(\Carbon\Carbon::FRIDAY);
            }
            
            // Fixer l'heure à 17:00:00
            $dateFin->setTime(17, 0, 0);
            
            $instance->date_fin = $dateFin->format('Y-m-d H:i:s');
        }

        // Échelle de notation par défaut
        if (!isset($instance->echelle_note_cible)) {
            $instance->echelle_note_cible = 40;
        }

        return $instance;
    }

    /**
     * Règles de validation avant la création.
     * 
     * Vérifie la présence obligatoire du groupe et du projet,
     * la cohérence chronologique des dates, et la correspondance des filières.
     *
     * @param array $data Données soumises passées par référence.
     * @throws \InvalidArgumentException Si validation échoue.
     * @throws BlException Si les règles métier échouent.
     */
    public function beforeCreateRules(array &$data)
    {
        // Vérification des champs obligatoires
        if (empty($data['groupe_id']) || empty($data['projet_id'])) {
            throw new \InvalidArgumentException("Le groupe et le projet sont obligatoires.");
        }

        // Vérification de la correspondance des filières
        $projet = \Modules\PkgCreationProjet\Models\Projet::find($data['projet_id']);
        $groupe = \Modules\PkgApprenants\Models\Groupe::find($data['groupe_id']);
        
        if ($projet && $groupe && $projet->filiere_id !== $groupe->filiere_id) {
            throw new BlException("Impossible de créer l'affectation : Le groupe sélectionné ne fait pas partie de la filière du projet.");
        }

        // Vérification de la cohérence des dates
        if (!empty($data['date_debut']) && !empty($data['date_fin']) && $data['date_debut'] > $data['date_fin']) {
            throw new \InvalidArgumentException("La date de début ne peut pas être après la date de fin.");
        }
    }

    /**
     * Règles de validation avant la mise à jour.
     * 
     * Vérifie la cohérence chronologique des dates et la correspondance des filières.
     *
     * @param array $data Données soumises passées par référence.
     * @param mixed $id Identifiant de l'affectation.
     * @throws \InvalidArgumentException Si validation échoue.
     * @throws BlException Si les règles métier échouent.
     */
    public function beforeUpdateRules(array &$data, $id = null)
    {
        if (!empty($data['groupe_id']) && !empty($data['projet_id'])) {
            $projet = \Modules\PkgCreationProjet\Models\Projet::find($data['projet_id']);
            $groupe = \Modules\PkgApprenants\Models\Groupe::find($data['groupe_id']);
            
            if ($projet && $groupe && $projet->filiere_id !== $groupe->filiere_id) {
                throw new BlException("Impossible de mettre à jour l'affectation : Le groupe sélectionné ne fait pas partie de la filière du projet.");
            }
        }

        if (!empty($data['date_debut']) && !empty($data['date_fin']) && $data['date_debut'] > $data['date_fin']) {
            throw new \InvalidArgumentException("La date de début ne peut pas être après la date de fin.");
        }
    }

    /**
     * Actions post-création.
     * 
     * Nettoie les tâches N1 existantes si tous les apprenants (y compris ceux de ce groupe)
     * ont déjà validé le chapitre associé.
     *
     * @param mixed $affectationProjet L'instance créée.
     */
    public function afterCreateRules($affectationProjet)
    {
        if ($affectationProjet->projet_id) {
            $tacheService = new \Modules\PkgCreationTache\Services\TacheService();
            // Récupérer les tâches liés à des chapitres pour ce projet
            $taches = \Modules\PkgCreationTache\Models\Tache::where('projet_id', $affectationProjet->projet_id)
                ->whereNotNull('chapitre_id')
                ->get();

            foreach ($taches as $tache) {
                // Utilisation de la méthode centralisée
                // Utilisation de RealisationChapitreService
                $realisationChapitreService = app(\Modules\PkgApprentissage\Services\RealisationChapitreService::class);
                if ($realisationChapitreService->checkAllLearnersValidatedChapter($affectationProjet->projet_id, $tache->chapitre_id)) {
                    // Supprimer la tâche car redondante (tous les apprenants l'ont déjà faite)
                    $tacheService->destroy($tache->id);
                }
            }
        }
    }

    /**
     * Actions post-mise à jour.
     * 
     * Synchronise la liste des réalisations de projet si la composition
     * du groupe ou sous-groupe a changé, et met à jour les évaluations.
     *
     * @param mixed $affectationProjet L'entité mise à jour.
     * @param int $id ID de l'entité.
     */
    public function afterUpdateRules($affectationProjet, $id)
    {
        $realisationProjetService = new RealisationProjetService();

        $nouveauxApprenants = collect();

        if ($affectationProjet->sousGroupe) {
            $nouveauxApprenants = $affectationProjet->sousGroupe->apprenants;
        } elseif ($affectationProjet->groupe) {
            $nouveauxApprenants = $affectationProjet->groupe->apprenants;
        }

        // Récupération des réalisations existantes
        $realisationProjetService->syncApprenantsAvecRealisationProjets(
            $affectationProjet,
            $nouveauxApprenants
        );

        (new EvaluationRealisationProjetService())->SyncEvaluationRealisationProjet($affectationProjet);
    }

    /**
     * Règles de validation avant suppression.
     * 
     * Empêche la suppression si des réalisations associées ont déjà commencé
     * (état différent de 'TODO').
     *
     * @param mixed $affectationProjet L'entité à supprimer.
     * @throws BlException Si des réalisations sont en cours.
     */
    public function beforeDeleteRules($affectationProjet)
    {
        // Vérifier s’il existe des réalisations liées dont l'état ≠ "TODO"
        $realisationProjets = $affectationProjet->realisationProjets()->with('etatsRealisationProjet')->get();

        $hasNonTodo = $realisationProjets->contains(function ($realisation) {
            return optional($realisation->etatsRealisationProjet)->code !== 'TODO'; // état initial
        });

        if ($hasNonTodo) {
            throw new BlException("Impossible de supprimer cette affectation : </br> au moins une réalisation de projet a un état différent de 'À faire'. </br> Veuillez réinitialiser tous les états à 'À faire' avant de procéder à la suppression.");
        }
    }
}
