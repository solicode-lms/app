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
        Schema::create('taches', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('titre'); // Titre de la tâche
            $table->longText('description')->nullable(); // Description optionnelle
            $table->dateTime('dateDebut')->nullable(); // Date de début de la tâche (optionnelle)
            $table->dateTime('dateFin')->nullable(); // Date de fin de la tâche (optionnelle)
            $table->string('reference')->unique(); // Référence unique pour la tâche

            // Relations
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade'); // Une tâche appartient à un projet
            $table->foreignId('priorite_tache_id')->nullable()->constrained('priorite_taches')->onDelete('set null'); // Une tâche a une priorité qui peut être supprimée

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
        Schema::dropIfExists('taches');
    }
};
