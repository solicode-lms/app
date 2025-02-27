<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ne pas supprimer les projet en cas de supprimer un formateur
        // Il faut supprimer les projet manuellement
        Schema::table('projets', function (Blueprint $table) {
            // Supprimer la contrainte existante (qui avait onDelete('cascade'))
            $table->dropForeign(['formateur_id']);

            // Ajouter une nouvelle contrainte avec onDelete('restrict')
            $table->foreign('formateur_id')
                  ->references('id')
                  ->on('formateurs')
                  ->onDelete('restrict'); // Empêche la suppression si des projets existent encore
        });

        // Delete affectation de projet on delete projet 
        Schema::table('affectation_projets', function (Blueprint $table) {
            $table->dropForeign(['projet_id']);
            $table->foreign('projet_id')
                  ->references('id')
                  ->on('projets')
                  ->onDelete('cascade');
        });

        // Delete RealisationProjet on Delete Affectation
        Schema::table('realisation_projets', function (Blueprint $table) {
            $table->dropForeign(['affectation_projet_id']);
            $table->foreign('affectation_projet_id')
                  ->references('id')
                  ->on('affectation_projets')
                  ->onDelete('cascade');
        });

        // Delete LivrableRealisation on delete RealisationProjet
        Schema::table('livrables_realisations', function (Blueprint $table) {
            $table->dropForeign(['realisation_projet_id']);
            $table->foreign('realisation_projet_id')
                ->references('id')
                ->on('realisation_projets')
                ->onDelete('cascade');
        });
    

        // Delete Validation on delete RealisationProjet
        Schema::table('validations', function (Blueprint $table) {
            $table->dropForeign(['realisation_projet_id']);
            $table->foreign('realisation_projet_id')
                ->references('id')
                ->on('realisation_projets')
                ->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
};
