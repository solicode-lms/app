<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('realisation_projets', function (Blueprint $table) {
            // 🗑️ Supprimer l'ancienne colonne
            if (Schema::hasColumn('realisation_projets', 'progression_cache')) {
                $table->dropColumn('progression_cache');
            }

            // ✅ Ajouter les deux nouvelles
            $table->double('progression_execution_cache')->nullable()->after('bareme_cache');
            $table->double('progression_validation_cache')->nullable()->after('progression_execution_cache');
        });
    }

    public function down(): void
    {
        Schema::table('realisation_projets', function (Blueprint $table) {
            // 🔄 Recréer l'ancienne colonne
            $table->double('progression_cache')->nullable()->after('bareme_cache');

            // 🔄 Supprimer les deux nouvelles
            $table->dropColumn([
                'progression_execution_cache',
                'progression_validation_cache',
            ]);
        });
    }
};
