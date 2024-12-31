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
        Schema::create('projets', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('titre'); // Titre du projet
            $table->text('travail_a_faire'); // Travail à faire
            $table->text('critere_de_travail'); // Critères de travail
            $table->text('description'); // Description générale
            $table->date('date_debut'); // Date de début
            $table->date('date_fin'); // Date de fin
            $table->foreignId('formateur_id')->constrained('formateurs')->onDelete('cascade'); // Relation avec Projet
            $table->timestamps(); // Champs created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projets');
    }
};
