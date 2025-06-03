<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluation_realisation_projets', function (Blueprint $table) {
            // Clé primaire auto-incrémentée
            $table->id();

            // Date de l’évaluation (type date)
            $table->date('date_evaluation');

            // Remarques (texte libre, nullable si vous préférez)
            $table->text('remarques')->nullable();

            // Référence (obligatoire, string unique)
            $table->string('reference')->unique();

            // Clé étrangère vers realisation_projets
            $table->unsignedBigInteger('realisation_projet_id');
            $table->foreign('realisation_projet_id')
                  ->references('id')
                  ->on('realisation_projets')
                  ->onDelete('cascade');

            // Clé étrangère vers evaluateurs
            $table->unsignedBigInteger('evaluateur_id');
            $table->foreign('evaluateur_id')
                  ->references('id')
                  ->on('evaluateurs')
                  ->onDelete('restrict');

            // Clé étrangère vers etat_evaluation_realisation_projets
            $table->unsignedBigInteger('etat_evaluation_projet_id')->nullable();
            $table->foreign('etat_evaluation_projet_id')
                  ->references('id')
                  ->on('etat_evaluation_projets')
                  ->onDelete('set null');

            // Horodatages Laravel (created_at, updated_at)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_realisation_projets');
    }
};
