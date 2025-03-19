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
        Schema::create('realisation_chapitres', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->date('date_debut'); // Date de début de la réalisation du chapitre
            $table->date('date_fin')->nullable(); // Date de fin de la réalisation du chapitre
            $table->string('reference')->unique(); // Référence unique

            // Relations
            $table->foreignId('chapitre_id')
                  ->constrained('chapitres')
                  ->onDelete('cascade'); // Relation avec les chapitres

            $table->foreignId('realisation_formation_id')
                  ->constrained('realisation_formations')
                  ->onDelete('cascade'); // Relation avec les apprenants

            $table->foreignId('etat_chapitre_id')
                  ->nullable()
                  ->constrained('etat_chapitres')
                  ->nullOnDelete(); 

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
        Schema::dropIfExists('realisation_chapitres');
    }
};
