<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('realisation_competences', function (Blueprint $table) {
            $table->id();

            // Référence UUID unique (règle SoliLMS)
            $table->string('reference')->unique();

            // Dates de suivi
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();

            // Caches pour éviter recalculs
            $table->double('progression_cache')->default(0);
            $table->double('note_cache')->nullable();
            $table->double('bareme_cache')->nullable();

            // Commentaires et suivi
            $table->text('commentaire_formateur')->nullable();
            $table->dateTime('dernier_update')->nullable();

            // Relations principales
            $table->foreignId('apprenant_id')
                  ->constrained('apprenants')
                  ->onDelete('cascade');

            $table->foreignId('competence_id')
                  ->constrained('competences')
                  ->onDelete('cascade');

            // État de réalisation
            $table->foreignId('etat_realisation_competence_id')
                  ->nullable()
                  ->constrained('etat_realisation_competences')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('realisation_competences');
    }
};
