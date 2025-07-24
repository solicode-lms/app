<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisation_chapitres', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();

            // Colonnes principales
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->text('commentaire_formateur')->nullable();

            // Relation vers realisation_uas
            $table->foreignId('realisation_ua_id')
                  ->constrained('realisation_uas')
                  ->onDelete('cascade');

            // Relation vers realisation_taches (SET NULL)
            $table->foreignId('realisation_tache_id')
                  ->nullable()
                  ->constrained('realisation_taches')
                  ->nullOnDelete();

            // Relation vers chapitres
            $table->foreignId('chapitre_id')
                  ->constrained('chapitres')
                  ->onDelete('cascade');

            // Relation vers etat_realisation_chapitres (nom court pour la clé)
            $table->foreignId('etat_realisation_chapitre_id')
                  ->nullable();

            $table->foreign('etat_realisation_chapitre_id', 'fk_rchap_etat')
                  ->references('id')
                  ->on('etat_realisation_chapitres')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisation_chapitres');
    }
};
