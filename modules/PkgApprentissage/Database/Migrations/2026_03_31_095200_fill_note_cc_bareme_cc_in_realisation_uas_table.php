<?php

use Illuminate\Database\Migrations\Migration;
use Modules\PkgApprentissage\Models\RealisationUa;
use Modules\PkgApprentissage\Services\RealisationUaService;

return new class extends Migration
{
    /**
     * Recalcule note_cc_cache et bareme_cc_cache pour toutes les realisation_uas existantes
     * en utilisant RealisationUaService::calculerProgression() (logique métier unifiée).
     */
    public function up(): void
    {

         // 🔄 Recalcul global pour remplir le pourcentage_non_valide_cache des données existantes
        // Cela lance un effet domino : Ua -> Micro-compétence -> Compétence -> Module
        RealisationUa::chunk(50, function ($uas) {
            $service = new RealisationUaService();
            foreach ($uas as $ua) {
                $service->calculerProgression($ua);
            }
        });
    }

    public function down(): void
    {
        RealisationUa::query()->update([
            'note_cc_cache'   => null,
            'bareme_cc_cache' => null,
        ]);
    }
};
