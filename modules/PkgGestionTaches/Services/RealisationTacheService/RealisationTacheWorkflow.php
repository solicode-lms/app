<?php

namespace Modules\PkgRealisationTache\Services\RealisationTacheService;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgAutorisation\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Modules\PkgRealisationTache\Database\Seeders\EtatRealisationTacheSeeder;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Illuminate\Database\Eloquent\Builder;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgRealisationTache\Services\WorkflowTacheService;

trait RealisationTacheWorkflow
{
    /**
     * Liste des codes de workflows imposant une validation de priorité après la modification
     * d'un objet : realisationTache
     * @param mixed $workflowCode
     * @return bool
     */
    protected function workflowExigeRespectDesPriorites(?string $workflowCode): bool
    {
        if (!$workflowCode) {
            return false;
        }
        // Liste des codes de workflows imposant une validation de priorité
        $workflowsBloquants = [
            'EN_COURS', // adapte selon tes besoins
            'EN_VALIDATION',
            'TERMINEE'
        ];
        return in_array($workflowCode, $workflowsBloquants);
    }

    /**
     * Vérifier que les tâches moins prioritaire sont terminé 
     * @param $realisationTache
     * @param mixed $workflowCode
     * @return void
     */
    protected function verifierTachesMoinsPrioritairesTerminees(RealisationTache $realisationTache,$workflowCode): void
    {
        // Charger les relations nécessaires
        $realisationTache->loadMissing('etatRealisationTache.workflowTache', 'tache.prioriteTache');

        // Appliquer la règle seulement si le workflow le demande
        if (!$this->workflowExigeRespectDesPriorites($workflowCode)) {
            return;
        }

        $realisationProjetId = $realisationTache->realisation_projet_id;
        $tache = $realisationTache->tache;

        if ($tache && $tache->prioriteTache) {
            $ordreActuel = $tache->prioriteTache->ordre;

            // Les états considérés comme "terminés" ou non bloquants
            $etatsFinaux = ['TERMINEE', 'EN_VALIDATION'];

            $tachesBloquantes = RealisationTache::where('realisation_projet_id', $realisationProjetId)
                ->whereHas('tache.prioriteTache', function ($query) use ($ordreActuel) {
                    $query->where('ordre', '<', $ordreActuel);
                })
                ->where(function ($query) use ($etatsFinaux) {
                    $query
                        ->whereHas('etatRealisationTache.workflowTache', function ($q) use ($etatsFinaux) {
                            $q->whereNotIn('code', $etatsFinaux);
                        })
                        ->orDoesntHave('etatRealisationTache');
                })
                ->with('tache') // Charger les noms des tâches
                ->get();

            if ($tachesBloquantes->isNotEmpty()) {
                $nomsTaches = $tachesBloquantes->pluck('tache.titre')->filter()->map(function ($nom) {
                    return "<li>" . e($nom) . "</li>";
                })->join('');

                $message = "<p> Impossible de passer à cet état : les tâches plus prioritaires  <br> suivantes ne sont pas encore terminées</p><ul>$nomsTaches</ul>";

                throw ValidationException::withMessages([
                    'etat_realisation_tache_id' => $message
                ]);
            }
        }
    }



        /**
     * Met à jour automatiquement l'état de la tâche en "Révision nécessaire"
     * si l'attribut `remarques_formateur` est modifié par un formateur.
     *
     * - Si l'état "REVISION_NECESSAIRE" n'existe pas pour ce formateur,
     *   il est automatiquement créé à partir du workflow correspondant.
     * - Si l'état actuel est déjà "REVISION_NECESSAIRE", aucun changement n’est effectué.
     *
     * @param RealisationTache $record L'enregistrement de la réalisation de tâche concerné.
     * @param array $data Les nouvelles données soumises contenant possiblement `remarques_formateur`.
     *
     * @return void
     */
    public function mettreAJourEtatRevisionSiRemarqueModifiee(RealisationTache $record, array &$data)
    {

        // 🛡️ Si l'utilisateur  n'est pas  formateur, on sort sans rien faire
        if (!Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
            return;
        }

        // Vérifier si la remarque formateur a changé
        if (!array_key_exists('remarques_formateur', $data)) {
            return;
        }

        $ancienneRemarque = $record->remarques_formateur;
        $nouvelleRemarque = $data['remarques_formateur'];

        if ($ancienneRemarque === $nouvelleRemarque) {
            return;
        }

        // Vérifier l'état actuel
        $etatActuel = $record->etatRealisationTache;
        if ($etatActuel && $etatActuel->reference === 'REVISION_NECESSAIRE') {
            return; // Déjà en "Révision nécessaire"
        }

        // Chercher ou créer l'état REVISION_NECESSAIRE pour le formateur connecté
        $wk_revision_necessaire = (new WorkflowTacheService())->getOrCreateWorkflowRevision();

        $etatRevision = EtatRealisationTache::firstOrCreate([
            'workflow_tache_id' => $wk_revision_necessaire->id ,
            'formateur_id' => Auth::user()->formateur->id ?? null,
        ], [
            'nom' => $wk_revision_necessaire->titre,
            'description' => $wk_revision_necessaire->description,
            'is_editable_only_by_formateur' => false,
            'sys_color_id' => $wk_revision_necessaire->sys_color_id, // Choisir une couleur par défaut appropriée
            'workflow_tache_id' => $wk_revision_necessaire->id,
        ]);

        // La modifcation sera efectuer par update
        $data["etat_realisation_tache_id"] = $etatRevision->id;
        
        
    }

}