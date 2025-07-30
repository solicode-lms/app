<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('etats_realisation_projets', function (Blueprint $table) {
            // Suppression de la colonne formateur_id et de sa contrainte
            $table->dropForeign(['formateur_id']);
            $table->dropColumn('formateur_id');

            // Ajout des colonnes ordre et code
            $table->integer('ordre')->default(0)->after('id');
            $table->string('code')->unique()->after('ordre');
        });
    }

    public function down(): void
    {
        Schema::table('etats_realisation_projets', function (Blueprint $table) {
            // Suppression des nouvelles colonnes
            $table->dropColumn(['ordre', 'code']);

            // Ré-ajout de la colonne formateur_id
            $table->foreignId('formateur_id')
                  ->constrained('formateurs')
                  ->onDelete('cascade');
        });
    }
};
