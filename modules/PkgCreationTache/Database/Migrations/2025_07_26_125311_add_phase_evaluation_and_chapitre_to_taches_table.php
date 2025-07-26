<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            // Relation avec PhaseEvaluation
            if (!Schema::hasColumn('taches', 'phase_evaluation_id')) {
                $table->foreignId('phase_evaluation_id')
                    ->nullable()
                    ->constrained('phase_evaluations')
                    ->nullOnDelete();
            }

            // Relation avec Chapitre
            if (!Schema::hasColumn('taches', 'chapitre_id')) {
                $table->foreignId('chapitre_id')
                    ->nullable()
                    ->constrained('chapitres')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            if (Schema::hasColumn('taches', 'phase_evaluation_id')) {
                $table->dropForeign(['phase_evaluation_id']);
                $table->dropColumn('phase_evaluation_id');
            }

            if (Schema::hasColumn('taches', 'chapitre_id')) {
                $table->dropForeign(['chapitre_id']);
                $table->dropColumn('chapitre_id');
            }
        });
    }
};
