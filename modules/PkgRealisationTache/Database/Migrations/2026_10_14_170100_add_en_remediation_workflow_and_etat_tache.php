<?php

use Illuminate\Database\Migrations\Migration;
use Modules\Core\Models\SysColor;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Services\WorkflowTacheService;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $colorId = SysColor::where('reference', 'warning')->value('id');

        $workflow = WorkflowTache::firstOrCreate(
            ['code' => 'EN_REMEDIATION'],
            [
                'titre' => 'En remédiation',
                'description' => 'La tâche prototype nécessite une consolidation didactique avant une nouvelle session Live Coding.',
                'reference' => 'EN_REMEDIATION',
                'ordre' => 11,
                'sys_color_id' => $colorId,
                'is_editable_only_by_formateur' => true,
            ]
        );

        // Synchroniser ou créer l'état dans EtatRealisationTache pour chaque formateur existant
        app(WorkflowTacheService::class)->resyncEtatsPourWorkflow($workflow);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $workflow = WorkflowTache::where('code', 'EN_REMEDIATION')->first();

        if ($workflow) {
            // Supprimer les états associés non utilisés
            app(WorkflowTacheService::class)->detachEtatsPourWorkflow($workflow);
            $workflow->delete();
        }
    }
};
