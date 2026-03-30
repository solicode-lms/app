<?php

use Illuminate\Database\Migrations\Migration;
use Modules\PkgApprentissage\Models\RealisationUa;
use Modules\PkgApprentissage\Services\RealisationUaService;

return new class extends Migration
{
    /**
     * Run the migrations.
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Aucun retour en arrière n'est nécessaire car il s'agit d'un calcul de données et non d'une structure
    }
};
