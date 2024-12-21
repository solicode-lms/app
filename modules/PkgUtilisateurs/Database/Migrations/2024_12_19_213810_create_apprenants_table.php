<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('apprenants', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('prenom_arab');
            $table->string('nom_arab');
            $table->string('tele_num');
            $table->string('profile_image');

            $table->timestamps();
           
            $table->foreignId('groupe_id')->constrained('groupes');
            $table->foreignId('niveaux_scolaires_id')->constrained('niveaux_scolaires');
            $table->foreignId('ville_id')->constrained('villes');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apprenants');
    }
};
