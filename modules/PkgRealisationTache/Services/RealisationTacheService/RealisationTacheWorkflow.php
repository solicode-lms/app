<?php

namespace Modules\PkgRealisationTache\Services\RealisationTacheService;


use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Role;
use Illuminate\Validation\ValidationException;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Services\WorkflowTacheService;

trait RealisationTacheWorkflow
{
    /**
     * Liste des codes de workflows imposant une validation de priorité après la modification
     */
    protected function workflowExigeRespectDesPriorites(?string $workflowCode): bool
    {
        if (!$workflowCode) {
            return false;
        }

        $workflowsBloquants = [
            'IN_PROGRESS',
            'TO_APPROVE',
            'APPROVED',
        ];

        return in_array($workflowCode, $workflowsBloquants, true);
    }

    /**
     * Vérifie que toutes les tâches de priorité inférieure soient terminées
     */
    protected function verifierTachesMoinsPrioritairesTerminees(RealisationTache $realisationTache, $workflowCode): void
    {
        if (!$this->workflowExigeRespectDesPriorites($workflowCode)) {
            return;
        }

        $realisationTache->loadMissing('etatRealisationTache.workflowTache', 'tache');

        $projetId = $realisationTache->realisation_projet_id;
        $prioriteActuelle = $realisationTache->tache?->priorite ?? null;

        if ($prioriteActuelle === null) {
            return;
        }

        $etatsFinaux = ['APPROVED', 'TO_APPROVE', 'READY_FOR_LIVE_CODING','NOT_VALIDATED'];

        $tachesBloquantes = RealisationTache::where('realisation_projet_id', $projetId)
            ->whereHas('tache', function ($query) use ($prioriteActuelle) {
                $query->whereNotNull('priorite')->where('priorite', '<', $prioriteActuelle);
            })
            ->where(function ($query) use ($etatsFinaux) {
                $query->whereDoesntHave('etatRealisationTache')
                      ->orWhereHas('etatRealisationTache.workflowTache', function ($q) use ($etatsFinaux) {
                          $q->whereNotIn('code', $etatsFinaux);
                      });
            })
            ->with('tache')
            ->get();

        if ($tachesBloquantes->isNotEmpty()) {
            $nomsTaches = $tachesBloquantes->pluck('tache.titre')->filter()->map(fn($nom) => "<li>" . e($nom) . "</li>")->join('');

            throw ValidationException::withMessages([
                'etat_realisation_tache_id' => "<p>Impossible de passer à cet état : les tâches plus prioritaires suivantes ne sont pas encore terminées</p><ul>$nomsTaches</ul>"
            ]);
        }
    }

    /**
     * Met à jour l’état de la tâche si une remarque formateur est ajoutée ou modifiée
     */
    public function mettreAJourEtatRevisionSiRemarqueModifiee(RealisationTache $record, array &$data): void
    {
        if (!Auth::user()?->hasRole(Role::FORMATEUR_ROLE)) {
            return;
        }

        if (!array_key_exists('remarques_formateur', $data)) {
            return;
        }

        if ($record->remarques_formateur === $data['remarques_formateur']) {
            return;
        }

        if ($record->etatRealisationTache?->reference === 'REVISION_NECESSAIRE') {
            return;
        }

        $wk = (new WorkflowTacheService())->getOrCreateWorkflowRevision();

        $etatRevision = EtatRealisationTache::firstOrCreate([
            'workflow_tache_id' => $wk->id,
            'formateur_id' => Auth::user()?->formateur->id,
        ], [
            'nom' => $wk->titre,
            'description' => $wk->description,
            'is_editable_only_by_formateur' => false,
            'sys_color_id' => $wk->sys_color_id,
        ]);

        $data['etat_realisation_tache_id'] = $etatRevision->id;
    }
}
