<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Suppression des évaluations orphelines (sans projet associé).
     */
    public function up()
    {
        // Attention : action destructive et irréversible !
        DB::statement('DELETE FROM evaluation_realisation_taches WHERE evaluation_realisation_projet_id IS NULL');
    }

    /**
     * Cette migration est **non réversible** (down = vide).
     */
    public function down()
    {
        // Aucune action possible pour restaurer les données supprimées.
    }
};
