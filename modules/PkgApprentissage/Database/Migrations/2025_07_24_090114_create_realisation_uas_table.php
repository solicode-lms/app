<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisation_uas', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();

            // Colonnes principales
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->float('progression_cache')->default(0);
            $table->float('note_cache')->default(0);
            $table->float('bareme_cache')->default(0);
            $table->text('commentaire_formateur')->nullable();

            // Relation vers realisation_micro_competences
            $table->foreignId('realisation_micro_competence_id')
                  ->constrained('realisation_micro_competences')
                  ->onDelete('cascade');

            // Relation vers unite_apprentissages
            $table->foreignId('unite_apprentissage_id')
                  ->constrained('unite_apprentissages')
                  ->onDelete('cascade');

            // Relation vers etat_realisation_uas (nom court pour la clé)
            $table->foreignId('etat_realisation_ua_id')
                  ->nullable();

            $table->foreign('etat_realisation_ua_id', 'fk_rua_etat')
                  ->references('id')
                  ->on('etat_realisation_uas')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisation_uas');
    }
};
