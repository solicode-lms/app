<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affectation_projets', function (Blueprint $table) {
            // Échelle cible pour recalculer la note (nullable par défaut)
            $table->unsignedInteger('echelle_note_cible')
                  ->nullable()
                  ->after('description')
                  ->comment('Échelle cible (ex: 50) pour recalculer la note brute');
        });
    }

    public function down(): void
    {
        Schema::table('affectation_projets', function (Blueprint $table) {
            $table->dropColumn('echelle_note_cible');
        });
    }
};
