<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('realisation_uas', function (Blueprint $table) {
            $table->decimal('pourcentage_non_valide_cache', 5, 2)->default(0)->after('taux_rythme_cache');
        });

        Schema::table('realisation_micro_competences', function (Blueprint $table) {
            $table->decimal('pourcentage_non_valide_cache', 5, 2)->default(0)->after('taux_rythme_cache');
        });

        Schema::table('realisation_competences', function (Blueprint $table) {
            $table->decimal('pourcentage_non_valide_cache', 5, 2)->default(0)->after('taux_rythme_cache');
        });

        Schema::table('realisation_modules', function (Blueprint $table) {
            $table->decimal('pourcentage_non_valide_cache', 5, 2)->default(0)->after('taux_rythme_cache');
        });
    }

    public function down()
    {
        Schema::table('realisation_uas', function (Blueprint $table) {
            $table->dropColumn('pourcentage_non_valide_cache');
        });

        Schema::table('realisation_micro_competences', function (Blueprint $table) {
            $table->dropColumn('pourcentage_non_valide_cache');
        });

        Schema::table('realisation_competences', function (Blueprint $table) {
            $table->dropColumn('pourcentage_non_valide_cache');
        });

        Schema::table('realisation_modules', function (Blueprint $table) {
            $table->dropColumn('pourcentage_non_valide_cache');
        });
    }
};
