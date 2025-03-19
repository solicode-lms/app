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
        Schema::create('realisation_formations', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->date('date_debut'); // Date de début de la réalisation
            $table->date('date_fin')->nullable(); // Date de fin de la réalisation
            $table->string('reference')->unique(); // Référence unique

            // Relations
            $table->foreignId('formation_id')
                  ->constrained('formations')
                  ->onDelete('cascade'); // Relation avec formations (chaque réalisation appartient à une formation)

            $table->foreignId('apprenant_id')
                  ->constrained('apprenants')
                  ->onDelete('cascade'); // Relation avec apprenants (chaque réalisation est associée à un apprenant)

            $table->foreignId('etat_formation_id')
                  ->nullable()
                  ->constrained('etat_formations')
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
        Schema::dropIfExists('realisation_formations');
    }
};
