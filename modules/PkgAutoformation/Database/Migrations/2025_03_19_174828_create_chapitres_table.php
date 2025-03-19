<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapitres', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('nom')->unique(); // Nom unique du chapitre
            $table->string('lien')->nullable(); // Lien vers une ressource externe
            $table->integer('coefficient')->default(1); // Coefficient d'importance du chapitre
            $table->longText('description')->nullable(); // Description détaillée
            $table->integer('ordre'); // Ordre du chapitre dans la formation
            $table->boolean('is_officiel')->default(false); // Indique si le chapitre est officiel ou personnalisé
            $table->string('reference')->unique(); // Référence unique du chapitre

            // Relations
            $table->foreignId('formation_id')
                  ->constrained('formations')
                  ->onDelete('cascade'); // Relation avec formations (chaque chapitre appartient à une formation)

            $table->foreignId('niveau_competence_id')
                  ->nullable()
                  ->constrained('niveau_competences')
                  ->nullOnDelete(); // Relation avec niveaux_competences (chaque chapitre a un niveau de compétence requis)

            $table->foreignId('formateur_id')
                  ->nullable()
                  ->constrained('formateurs')
                  ->nullOnDelete(); // Relation avec formateurs (chapitre défini par un formateur)

            $table->foreignId('chapitre_officiel_id')
                  ->nullable()
                  ->constrained('chapitres')
                  ->nullOnDelete(); // Relation avec formateurs (chapitre défini par un formateur)

            $table->timestamps(); // Champs created_at et updated_at
        });
    }

    /**
     * Annuler les migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapitres');
    }
};
