<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisation_micro_competences', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->float('progression_cache')->default(0);
            $table->float('note_cache')->default(0);
            $table->float('bareme_cache')->default(0);
            $table->text('commentaire_formateur')->nullable();
            $table->dateTime('dernier_update')->nullable();

            // Relations principales
            $table->foreignId('apprenant_id')
                  ->constrained('apprenants')
                  ->onDelete('cascade');

            $table->foreignId('micro_competence_id')
                  ->constrained('micro_competences')
                  ->onDelete('cascade');

            // Relation avec clé étrangère nommée
            $table->foreignId('etat_realisation_micro_competence_id')
                  ->nullable();

            $table->foreign('etat_realisation_micro_competence_id', 'fk_rmcomp_etat')
                  ->references('id')
                  ->on('etat_realisation_micro_competences')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisation_micro_competences');
    }
};
