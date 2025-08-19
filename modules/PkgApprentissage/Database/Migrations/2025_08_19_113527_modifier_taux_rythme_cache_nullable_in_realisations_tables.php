<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE realisation_uas MODIFY taux_rythme_cache DECIMAL(5,2) NULL");
        DB::statement("ALTER TABLE realisation_micro_competences MODIFY taux_rythme_cache DECIMAL(5,2) NULL");
        DB::statement("ALTER TABLE realisation_competences MODIFY taux_rythme_cache DECIMAL(5,2) NULL");
        DB::statement("ALTER TABLE realisation_modules MODIFY taux_rythme_cache DECIMAL(5,2) NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE realisation_uas MODIFY taux_rythme_cache DECIMAL(5,2) NOT NULL DEFAULT 0");
        DB::statement("ALTER TABLE realisation_micro_competences MODIFY taux_rythme_cache DECIMAL(5,2) NOT NULL DEFAULT 0");
        DB::statement("ALTER TABLE realisation_competences MODIFY taux_rythme_cache DECIMAL(5,2) NOT NULL DEFAULT 0");
        DB::statement("ALTER TABLE realisation_modules MODIFY taux_rythme_cache DECIMAL(5,2) NOT NULL DEFAULT 0");
    }
};
