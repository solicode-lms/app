<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Étape 1 : Ajouter la colonne filiere_id en nullable
        Schema::table('projets', function (Blueprint $table) {
            $table->unsignedBigInteger('filiere_id')->nullable()->after('formateur_id');
        });

        // Étape 2 : Remplir les anciennes lignes avec une valeur par défaut si nécessaire
        $defaultFiliereId = DB::table('filieres')->first()?->id ?? null;

        if ($defaultFiliereId) {
            DB::table('projets')->whereNull('filiere_id')->update(['filiere_id' => $defaultFiliereId]);
        }

        // Étape 3 : Modifier la colonne pour la rendre non nullable et ajouter la contrainte de clé étrangère
        Schema::table('projets', function (Blueprint $table) {
            $table->unsignedBigInteger('filiere_id')->nullable(false)->change();
            $table->foreign('filiere_id')->references('id')->on('filieres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projets', function (Blueprint $table) {
            $table->dropForeign(['filiere_id']);
            $table->dropColumn('filiere_id');
        });
    }
};