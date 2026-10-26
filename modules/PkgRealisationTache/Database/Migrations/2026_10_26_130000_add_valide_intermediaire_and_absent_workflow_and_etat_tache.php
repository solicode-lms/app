<?php

use Illuminate\Database\Migrations\Migration;
use Modules\Core\Models\SysColor;
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

        // 1. État : Validé intermédiaire
        $tealColorId = SysColor::where('reference', 'teal')->value('id') 
            ?? SysColor::where('reference', 'info')->value('id');

        $workflowValide = WorkflowTache::firstOrCreate(
            ['code' => 'VALIDE_INTERMEDIAIRE'],
            [
                'titre' => 'Validé intermédiaire',
                'description' => 'La tâche a franchi une étape de validation intermédiaire avant sa validation finale.',
                'reference' => 'VALIDE_INTERMEDIAIRE',
                'ordre' => 13,
                'sys_color_id' => $tealColorId,
                'is_editable_only_by_formateur' => true,
            ]
        );

        $workflowService->resyncEtatsPourWorkflow($workflowValide);

        // 2. État : Absent
        $secondaryColorId = SysColor::where('reference', 'secondary')->value('id') 
            ?? SysColor::where('reference', 'dark')->value('id');

        $workflowAbsent = WorkflowTache::firstOrCreate(
            ['code' => 'ABSENT'],
            [
                'titre' => 'Absent',
                'description' => 'L\'apprenant était absent lors de la séance consacrée à cette tâche.',
                'reference' => 'ABSENT',
                'ordre' => 14,
                'sys_color_id' => $secondaryColorId,
                'is_editable_only_by_formateur' => true,
            ]
        );

        $workflowService->resyncEtatsPourWorkflow($workflowAbsent);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $workflowService = app(WorkflowTacheService::class);

        // Supprimer l'état Absent
        $workflowAbsent = WorkflowTache::where('code', 'ABSENT')->first();
        if ($workflowAbsent) {
            $workflowService->detachEtatsPourWorkflow($workflowAbsent);
            $workflowAbsent->delete();
        }

        // Supprimer l'état Validé intermédiaire
        $workflowValide = WorkflowTache::where('code', 'VALIDE_INTERMEDIAIRE')->first();
        if ($workflowValide) {
            $workflowService->detachEtatsPourWorkflow($workflowValide);
            $workflowValide->delete();
        }
    }
};
