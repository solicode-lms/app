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
        Schema::create('commentaire_realisation_taches', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->longText('commentaire'); // Contenu du commentaire
            $table->dateTime('dateCommentaire'); // Date du commentaire
            $table->string('reference')->unique(); // Référence unique pour le commentaire

            // Relations
            $table->foreignId('realisation_tache_id')->constrained('realisation_taches')->onDelete('cascade'); // Un commentaire est lié à une réalisation de tâche
            $table->foreignId('formateur_id')->nullable()->constrained('formateurs')->onDelete('set null'); // Un commentaire peut être fait par un formateur
            $table->foreignId('apprenant_id')->nullable()->constrained('apprenants')->onDelete('set null'); // Un commentaire peut être fait par un apprenant

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
        Schema::dropIfExists('commentaire_realisation_taches');
    }
};
