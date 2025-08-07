<?php

namespace Modules\PkgRealisationTache\Observers;

use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgRealisationTache\Services\WorkflowTacheService;

class WorkflowTacheObserver
{
    protected WorkflowTacheService $service;

    public function __construct(WorkflowTacheService $service)
    {
        $this->service = $service;
    }

    /**
     * Lorsqu’un WorkflowTache est créé → synchroniser les états pour tous les formateurs.
     */
    public function created(WorkflowTache $workflowTache): void
    {
        $this->service->resyncEtatsPourWorkflow($workflowTache);
    }

    /**
     * Lorsqu’un WorkflowTache est modifié → mettre à jour les états liés (si non modifiés manuellement).
     */
    public function updated(WorkflowTache $workflowTache): void
    {
        $this->service->updateEtatsPourWorkflow($workflowTache);
    }

    /**
     * Lorsqu’un WorkflowTache est supprimé → détacher ou supprimer les états liés.
     */
    public function deleted(WorkflowTache $workflowTache): void
    {
        $this->service->detachEtatsPourWorkflow($workflowTache);
    }
}
