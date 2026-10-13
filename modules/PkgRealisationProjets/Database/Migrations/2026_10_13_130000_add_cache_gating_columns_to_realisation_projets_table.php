<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('realisation_projets', function (Blueprint $table) {
            if (!Schema::hasColumn('realisation_projets', 'cache_nombre_tentatives_prototype')) {
                $table->unsignedSmallInteger('cache_nombre_tentatives_prototype')->default(0)->after('cache_date_lancement');
            }
            if (!Schema::hasColumn('realisation_projets', 'cache_est_valide_prototype_premier_essai')) {
                $table->boolean('cache_est_valide_prototype_premier_essai')->nullable()->default(null)->after('cache_nombre_tentatives_prototype');
            }
            if (!Schema::hasColumn('realisation_projets', 'cache_date_validation_prototype')) {
                $table->dateTime('cache_date_validation_prototype')->nullable()->default(null)->after('cache_est_valide_prototype_premier_essai');
            }
            // Index avec nom court explicite pour respecter la limite MySQL de 64 caractères
            if (!Schema::hasIndex('realisation_projets', 'idx_rp_gating_premier_essai')) {
                $table->index('cache_est_valide_prototype_premier_essai', 'idx_rp_gating_premier_essai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realisation_projets', function (Blueprint $table) {
            if (Schema::hasIndex('realisation_projets', 'idx_rp_gating_premier_essai')) {
                $table->dropIndex('idx_rp_gating_premier_essai');
            }
            $columnsToDrop = [];
            if (Schema::hasColumn('realisation_projets', 'cache_nombre_tentatives_prototype')) {
                $columnsToDrop[] = 'cache_nombre_tentatives_prototype';
            }
            if (Schema::hasColumn('realisation_projets', 'cache_est_valide_prototype_premier_essai')) {
                $columnsToDrop[] = 'cache_est_valide_prototype_premier_essai';
            }
            if (Schema::hasColumn('realisation_projets', 'cache_date_validation_prototype')) {
                $columnsToDrop[] = 'cache_date_validation_prototype';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
