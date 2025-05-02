<?php

namespace Modules\PkgGestionTaches\Services\RealisationTacheService;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgAutorisation\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Modules\PkgGestionTaches\Database\Seeders\EtatRealisationTacheSeeder;
use Modules\PkgGestionTaches\Models\EtatRealisationTache;
use Modules\PkgGestionTaches\Models\RealisationTache;
use Illuminate\Database\Eloquent\Builder;
use Modules\PkgGestionTaches\Models\HistoriqueRealisationTache;
use Modules\PkgGestionTaches\Models\WorkflowTache;
use Modules\PkgGestionTaches\Services\HistoriqueRealisationTacheService;

trait RealisationTacheServiceCrud
{

    /**
     * Méthode utilisé pendant le calcule dynamique des champs pendant la l'édition et la création
     * si le champs a le data : data-calcule
     * @param mixed $realisationTache
     */
    public function dataCalcul($realisationTache)
    {
        // En Cas d'édit
        if(isset($realisationTache->id)){
          
        }
      
        return $realisationTache;
    }

    /**
     * affectation de dataDebut = now()
     * @param int $id
     */
    public function edit(int $id)
    {
        $entity = $this->model->find($id);
        if (is_null($entity->dateDebut)) {
            $entity->dateDebut = now()->toDateString(); // format YYYY-MM-DD sans heure
            $entity->save(); // il faut sauvegarder si tu veux que le changement soit persisté
        }

        // ✅ Marquer les notifications liées à cette realisation_tache comme lues
        $this->markNotificationsAsReadForRealisationTache($entity->id);


        return $entity;
    }

    // TODO : déplacer dans NotificationService
    /**
     * Marquer toutes les notifications liées à une realisation_tache comme lues.
     */
    protected function markNotificationsAsReadForRealisationTache(int $realisationTacheId): void
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        $notificationService = app(\Modules\PkgNotification\Services\NotificationService::class);

        $notifications = $notificationService->newQuery()
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->where('data->realisation_tache_id', $realisationTacheId) // ✅ Attention, syntaxe spéciale pour JSON
            ->get();

        foreach ($notifications as $notification) {
            $notificationService->markAsRead($notification->id);
        }
    }

    /**
     * Trie pardéfaut
     * 1️⃣ Trier par date de fin de l'affectation
     * 2️⃣ Ensuite par ordre de tâche
     * @param mixed $query
     */
    public function defaultSort($query)
    {
        return $query
            ->with(['realisationProjet.affectationProjet']) // Charger affectationProjet
            ->join('realisation_projets', 'realisation_taches.realisation_projet_id', '=', 'realisation_projets.id')
            ->join('affectation_projets', 'realisation_projets.affectation_projet_id', '=', 'affectation_projets.id')
            ->join('taches', 'realisation_taches.tache_id', '=', 'taches.id')
            ->orderBy('affectation_projets.date_fin', 'desc') // 1️⃣ Trier par date de fin de l'affectation
            ->orderBy('taches.ordre', 'asc') // 2️⃣ Ensuite par ordre de tâche
            ->select('realisation_taches.*'); // 🎯 Important pour éviter le problème de Model::hydrate
    }



    /**
     * Méthode contient les règles métier qui sont appliquer pendant l'édition
     * il est utilisée avec tous les méthode qui font update
     * @param mixed $record
     * @param array $data
     * @return void
     */
    public function update_bl($record, array &$data){

        $historiqueRealisationTacheService = new HistoriqueRealisationTacheService();
        $historiqueRealisationTacheService->enregistrerChangement($record,$data);
        $this->mettreAJourEtatRevisionSiRemarqueModifiee($record, $data);
        
        // 🛡️ Si l'utilisateur  est  formateur, on sort sans rien faire
        // pour ne pas appliquer la règle : Empêcher un apprenant d'affecter un état réservé aux formateurs
        if (Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
            return;
        }
            
        // Empêcher un apprenant d'affecter un état réservé aux formateurs
        if (!empty($data["etat_realisation_tache_id"])) {
            $etat_realisation_tache_id = $data["etat_realisation_tache_id"];
            $nouvelEtat = EtatRealisationTache::find($etat_realisation_tache_id);

            // Vérifier si le nouvel état existe
            if ($nouvelEtat) {
                if ($nouvelEtat->is_editable_only_by_formateur && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
                    throw ValidationException::withMessages([
                        'etat_realisation_tache_id' => "Seul un formateur peut affecter cet état de tâche."
                    ]);
                }
                // ✅ Vérifie le respect de la priorité selon le workflow
                $workflowCode = optional($nouvelEtat->workflowTache)->code;
                if ($this->workflowExigeRespectDesPriorites($workflowCode)) {
                    $this->verifierTachesMoinsPrioritairesTerminees($record,$workflowCode);
                }
            }

            // Vérification si l'état actuel existe et est modifiable uniquement par un formateur
            if ($record->etatRealisationTache) {
                if (
                    $record->etatRealisationTache->is_editable_only_by_formateur
                    && $record->etatRealisationTache->id != $etat_realisation_tache_id
                    && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)
                ) {
                    throw ValidationException::withMessages([
                        'etat_realisation_tache_id' => "Cet état de projet doit être modifié par le formateur."
                    ]);
                }
            }
        }
    }

}