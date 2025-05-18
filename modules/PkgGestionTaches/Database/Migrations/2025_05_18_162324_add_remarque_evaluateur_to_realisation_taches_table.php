<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('realisation_taches', function (Blueprint $table) {
            // Ajouter une colonne de texte pour les remarques de l'évaluateur
            $table->longText('remarque_evaluateur')->nullable()
                  ->after('note'); // ou après la colonne de votre choix
        });
    }

    public function down(): void
    {
        Schema::table('realisation_taches', function (Blueprint $table) {
            // Supprimer la colonne si on annule la migration
            $table->dropColumn('remarque_evaluateur');
        });
    }
};
