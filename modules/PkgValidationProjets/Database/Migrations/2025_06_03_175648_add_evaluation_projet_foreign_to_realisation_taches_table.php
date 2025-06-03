<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEvaluationProjetForeignToRealisationTachesTable extends Migration
{
    /**
     * Exécute la migration : ajoute la colonne et la contrainte.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluation_realisation_taches', function (Blueprint $table) {
            // 1) Si la colonne n'existe pas déjà, on l'ajoute
            if (! Schema::hasColumn('evaluation_realisation_taches', 'evaluation_realisation_projet_id')) {
                $table->unsignedBigInteger('evaluation_realisation_projet_id')
                      ->after('id') // ou après la colonne pertinent
                      ->nullable();

                // 2) Définir la clé étrangère
                $table->foreign('evaluation_realisation_projet_id',  'fk_evaltache_evalprojet')
                      ->references('id')
                      ->on('evaluation_realisation_projets')
                      ->nullable()
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Annule la migration : supprime la clé étrangère et la colonne.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluation_realisation_taches', function (Blueprint $table) {
            // 1) Vérifier si la colonne existe avant de la supprimer
            if (Schema::hasColumn('evaluation_realisation_taches', 'evaluation_realisation_projet_id')) {
                // 2) Retirer la contrainte FK en utilisant son nom explicite
                 $table->dropForeign('fk_evaltache_evalprojet');

                // 3) Supprimer la colonne
                $table->dropColumn('evaluation_realisation_projet_id');
            }
        });
    }
}
