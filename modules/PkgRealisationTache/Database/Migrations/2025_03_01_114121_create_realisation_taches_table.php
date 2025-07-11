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
        Schema::create('realisation_taches', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->dateTime('dateDebut')->nullable(); // Date de début de la réalisation
            $table->dateTime('dateFin')->nullable(); // Date de fin de la réalisation
            $table->string('reference')->unique(); // Référence unique pour la réalisation

            // Relations
            $table->foreignId('tache_id')->constrained('taches')->onDelete('cascade'); // Une réalisation est liée à une tâche
            $table->foreignId('realisation_projet_id')->constrained('realisation_projets')->onDelete('cascade'); // Une réalisation de tâche appartient à une réalisation de projet
            $table->foreignId('etat_realisation_tache_id')->nullable()->constrained('etat_realisation_taches')->onDelete('set null'); // État de la réalisation

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
        Schema::dropIfExists('realisation_taches');
    }
};
