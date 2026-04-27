<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Création de la table principale pour les labels de projets
        Schema::create('label_projets', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->longText('description')->nullable();
            
            // Relation avec Projet (Un projet a plusieurs labels)
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');
            
            // Gestion de la couleur (soit via SysColor, soit une chaîne hex/rgb classique)
            // Décommentez la ligne correspondante selon votre implémentation
            $table->foreignId('sys_color_id')->nullable()->constrained('sys_colors')->onDelete('set null');
            
            $table->string('reference')->unique();
            $table->timestamps();
        });

        // Table pivot pour la relation Many-to-Many entre Taches et Labels
        Schema::create('label_tache', function (Blueprint $table) {
            $table->foreignId('tache_id')->constrained('taches')->onDelete('cascade');
            $table->foreignId('label_id')->constrained('label_projets')->onDelete('cascade');
            $table->timestamps();
        });

        // Table pivot pour la relation Many-to-Many entre RealisationTaches et Labels
        Schema::create('label_realisation_tache', function (Blueprint $table) {
            $table->foreignId('realisation_tache_id')->constrained('realisation_taches')->onDelete('cascade');
            $table->foreignId('label_id')->constrained('label_projets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label_realisation_tache');
        Schema::dropIfExists('label_tache');
        Schema::dropIfExists('label_projets');
    }
};
