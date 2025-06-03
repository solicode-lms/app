<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations.
     *
     * @return void
     */
    public function up()
    {
        // On rend la colonne "note" nullable via SQL brut
        DB::statement("
            ALTER TABLE `evaluation_realisation_taches`
            MODIFY `note` FLOAT NULL
        ");
    }

    /**
     * Annule les migrations.
     *
     * @return void
     */
    public function down()
    {
        // On remet la colonne "note" en NOT NULL via SQL brut
        DB::statement("
            ALTER TABLE `evaluation_realisation_taches`
            MODIFY `note` FLOAT NOT NULL
        ");
    }
};
