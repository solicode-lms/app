<?php

namespace Modules\PkgGestionTaches\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\PkgGestionTaches\Services\Base\BaseTacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgNotification\Enums\NotificationType;
use Modules\PkgNotification\Services\NotificationService;

/**
 * Classe TacheService pour gérer la persistance de l'entité Tache.
 */
class TacheService extends BaseTacheService
{
    protected $ordreGroupColumn = "projet_id";

    public function dataCalcul($tache)
    {
        // En Cas d'édit
        if(isset($tache->id)){
          
        }
      
        return $tache;
    }


    public function paginate(array $params = [], int $perPage = 0, array $columns = ['*']): LengthAwarePaginator
    {
        $perPage = $perPage ?: $this->paginationLimit;
    
        return $this->model::withScope(function () use ($params, $perPage, $columns) {
            $query = $this->allQuery($params);
    
        
            // Calcul du nombre total des résultats filtrés
            $this->totalFilteredCount = $query->count();
    
            return $query->paginate($perPage, $columns);
        });
    }



    public function create(array|object $data)
    {
        $notificationService = new NotificationService();

        // Créer la tâche
        $tache = parent::create($data);
    
        // Vérifier si la tâche est bien créée et qu'elle est associée à un projet
        if ($tache && isset($tache->projet)) {
            // Récupérer tous les apprenants liés au projet via les affectations et réalisations
            $realisationProjets = $tache->projet->affectationProjets
                ->flatMap(fn($affectation) => $affectation->realisationProjets);
    
            // Instance du service RealisationTacheService
            $realisationTacheService = new \Modules\PkgGestionTaches\Services\RealisationTacheService();
    
          
            $formateur_id = Auth::user()->hasRole(Role::FORMATEUR_ROLE)
            ? Auth::user()->formateur?->id
            : null;
            $etatInitial = $formateur_id
            ? (new EtatRealisationTacheService())->getDefaultEtatByFormateurId($formateur_id)
            : null;

            // Création des réalisations des tâches pour les apprenants concernés
            foreach ($realisationProjets as $realisationProjet) {
                $realisationTache = $realisationTacheService->create([
                    'tache_id' => $tache->id,
                    'realisation_projet_id' => $realisationProjet->id, // Associer à la bonne réalisation de projet
                    'etat_realisation_tache_id' => $etatInitial?->id, // Valeur par défaut à définir si nécessaire
                    'dateDebut' => $tache->dateDebut,
                    'dateFin' => $tache->dateFin
                ]);

                $user_id = $realisationTache->realisationProjet->apprenant?->user_id;

                $notificationService->sendNotificationToReadData(
                    "realisationTache",  // Le modèle concerné
                    $realisationTache->id, // L'ID de l'entité
                    $user_id,              // L'utilisateur cible
                    "Nouvelle tâche attribuée : "  . $realisationTache->tache->titre, // ✅ titre personnalisé
                    "Vous avez une nouvelle tâche à réaliser : " . $realisationTache->tache->titre, // ✅ message personnalisé
                    NotificationType::NOUVELLE_TACHE->value // ✅ type personnalisé (optionnel)
                );
            }
        }
    
        return $tache;
    }

    /**
     * Met à jour un élément existant.
     *
     * @param mixed $id Identifiant de l'élément à mettre à jour.
     * @param array $data Données à mettre à jour.
     * @return Entity modifié
     */
    public function update($id, array $data)
    {
        return parent::update($id,$data);
    }

   /**
     * Récupérer les tâches associées aux projets d'un formateur donné.
     *
     * @param int $formateurId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTacheByFormateurId(int $formateurId)
    {
        return $this->model->whereHas('projet', function ($query) use ($formateurId) {
            $query->where('formateur_id', $formateurId);
        })->get();
    }

    /**
     * Récupérer les tâches associées aux projets d'un apprenant donné.
     *
     * @param int $apprenantId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTacheByApprenantId(int $apprenantId)
    {
        return $this->model->whereHas('realisationTaches', function ($query) use ($apprenantId) {
            $query->whereHas('realisationProjet', function ($q) use ($apprenantId) {
                $q->where('apprenant_id', $apprenantId);
            });
        })->get();
    }


    /**
     * Récupérer les tâches associées à une affectation de projet donnée.
     *
     * @param int $affectationProjetId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTacheByAffectationProjetId(int $affectationProjetId)
    {
        return $this->model->whereHas('projet', function ($query) use ($affectationProjetId) {
            $query->whereHas('affectationProjets', function ($q) use ($affectationProjetId) {
                $q->where('id', $affectationProjetId);
            });
        })->get();
    }


    public function allQuery(array $params = [],$query = null): Builder
    {
        $query = parent::allQuery($params,$query);

        // Joindre les tables Tache et PrioriteTache avec LEFT JOIN pour inclure les tâches sans priorité
        $query->leftJoin('priorite_taches', 'taches.priorite_tache_id', '=', 'priorite_taches.id')
                ->orderByRaw('COALESCE(priorite_taches.ordre, 9999) ASC') // Trier par priorité (les NULL en dernier)
                ->select('taches.*'); // Sélectionner les colonnes de la table principale

          return  $query;
    }

}
