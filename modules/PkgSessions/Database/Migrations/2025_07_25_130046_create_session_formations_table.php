<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_formations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // Identifiant unique
            $table->string('titre');
            $table->integer('ordre')->default(0);
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->text('jour_feries_vacances')->nullable();
            $table->string('thematique')->nullable();
            $table->longText('objectifs_pedagogique');
            $table->longText('remarques')->nullable();

            // Prototype
            $table->string('titre_prototype');
            $table->longText('description_prototype');
            $table->longText('contraintes_prototype')->nullable();

            // Projet
            $table->string('titre_projet');
            $table->longText('description_projet');
            $table->longText('contraintes_projet')->nullable();

            // Relations avec SET NULL
            $table->foreignId('filiere_id')
                  ->nullable()
                  ->constrained('filieres')
                  ->onDelete('set null');

            $table->foreignId('annee_formation_id')
                  ->nullable()
                  ->constrained('annee_formations')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_formations');
    }
};
