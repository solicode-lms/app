<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apprenant_konosies', function (Blueprint $table) {
            $table->id();
            $table->string('MatriculeEtudiant')->unique();
            $table->string('Nom', 100);
            $table->string('Prenom', 100);
            $table->char('Sexe', 1);
            $table->string('EtudiantActif');
            $table->string('Diplome', 100)->nullable();
            $table->string('Principale')->nullable();
            $table->string('LibelleLong', 255)->nullable();
            $table->string('CodeDiplome', 50)->nullable();
            $table->string('DateNaissance')->nullable();
            $table->string('DateInscription')->nullable();
            $table->string('LieuNaissance', 255)->nullable();
            $table->string('CIN', 50)->nullable();
            $table->string('NTelephone', 15)->nullable();
            $table->longText('Adresse')->nullable();
            $table->string('Nationalite', 100)->nullable();
            $table->string('Nom_Arabe', 100)->nullable();
            $table->string('Prenom_Arabe', 100)->nullable();
            $table->string('NiveauScolaire', 100)->nullable();
            $table->string('reference')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apprenant_konosys');
    }
};
