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
            $table->longText('travail_a_faire'); // Travail à faire
            $table->longText('critere_de_travail'); // Critères de travail
            $table->longText('description')->nullable(); // Description générale
            $table->integer('nombre_jour'); // Date de début
            $table->foreignId('formateur_id')->constrained('formateurs')->onDelete('cascade'); // Relation avec Projet
            $table->string('reference')->unique();
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
