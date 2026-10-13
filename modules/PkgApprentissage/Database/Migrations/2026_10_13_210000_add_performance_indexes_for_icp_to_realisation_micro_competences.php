<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('realisation_micro_competences', function (Blueprint $table) {
            $table->index(['apprenant_id', 'etat_realisation_micro_competence_id'], 'idx_rmc_appr_etat');
            $table->index(['micro_competence_id', 'etat_realisation_micro_competence_id'], 'idx_rmc_mc_etat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realisation_micro_competences', function (Blueprint $table) {
            $table->dropIndex('idx_rmc_appr_etat');
            $table->dropIndex('idx_rmc_mc_etat');
        });
    }
};
