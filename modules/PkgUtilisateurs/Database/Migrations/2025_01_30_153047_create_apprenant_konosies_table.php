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
        Schema::create('apprenant_konosies', function (Blueprint $table) {
            $table->id();
            $table->string('MatriculeEtudiant');
            $table->string('Nom');
            $table->string('Prenom');
            $table->string('Sexe');
            $table->string('EtudiantActif');
            $table->string('Diplome');
            $table->string('Principale');
            $table->string('LibelleLong');
            $table->string('CodeDiplome');
            $table->string('DateNaissance');
            $table->string('DateInscription');
            $table->string('LieuNaissance');
            $table->string('CIN');
            $table->string('NTelephone');
            $table->text('Adresse');
            $table->string('Nationalite');
            $table->string('Nom_Arabe');
            $table->string('Prenom_Arabe');
            $table->string('NiveauScolaire');
            $table->string('reference');

            $table->timestamps();
           

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apprenant_konosies');
    }
};
