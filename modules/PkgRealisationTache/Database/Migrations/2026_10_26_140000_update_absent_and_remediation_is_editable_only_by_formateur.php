<?php

use Illuminate\Database\Migrations\Migration;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgRealisationTache\Services\WorkflowTacheService;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $workflowService = app(WorkflowTacheService::class);

        // Mettre à jour is_editable_only_by_formateur à false pour ABSENT et EN_REMEDIATION
        $workflows = WorkflowTache::whereIn('code', ['ABSENT', 'EN_REMEDIATION'])->get();

        foreach ($workflows as $workflow) {
            $workflow->update([
                'is_editable_only_by_formateur' => false,
            ]);

            // Synchroniser les états de chaque formateur
            $workflowService->updateEtatsPourWorkflow($workflow);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $workflowService = app(WorkflowTacheService::class);

        $workflows = WorkflowTache::whereIn('code', ['ABSENT', 'EN_REMEDIATION'])->get();

        foreach ($workflows as $workflow) {
            $workflow->update([
                'is_editable_only_by_formateur' => true,
            ]);

            $workflowService->updateEtatsPourWorkflow($workflow);
        }
    }
};
