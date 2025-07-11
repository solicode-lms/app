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
        Schema::create('historique_realisation_taches', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->dateTime('dateModification'); // Date de la modification
            $table->longText('changement'); // Description des changements effectués
            $table->string('reference')->unique(); // Référence unique pour l’historique

            // Relations
            $table->foreignId('realisation_tache_id')->constrained('realisation_taches')->onDelete('cascade'); // Une entrée d'historique est liée à une réalisation de tâche

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
        Schema::dropIfExists('historique_realisation_taches');
    }
};
