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
        $colorId = SysColor::where('reference', 'warning')->value('id');

        $workflow = WorkflowTache::firstOrCreate(
            ['code' => 'A_VALIDER_LIVE_CODING'],
            [
                'titre' => 'À valider Live coding',
                'description' => 'La démonstration collective de Live Coding est terminée, en attente de validation finale par le formateur.',
                'reference' => 'A_VALIDER_LIVE_CODING',
                'ordre' => 12,
                'sys_color_id' => $colorId,
                'is_editable_only_by_formateur' => false,
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
        $workflow = WorkflowTache::where('code', 'A_VALIDER_LIVE_CODING')->first();

        if ($workflow) {
            // Supprimer les états associés non utilisés
            app(WorkflowTacheService::class)->detachEtatsPourWorkflow($workflow);
            $workflow->delete();
        }
    }
};
