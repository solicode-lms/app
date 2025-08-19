<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('realisation_uas', function (Blueprint $table) {
            $table->decimal('progression_ideal_cache', 5, 2)->default(0)->after('updated_at');
            $table->decimal('taux_rythme_cache', 5, 2)->default(0)->after('progression_ideal_cache');
        });

        Schema::table('realisation_micro_competences', function (Blueprint $table) {
            $table->decimal('progression_ideal_cache', 5, 2)->default(0)->after('updated_at');
            $table->decimal('taux_rythme_cache', 5, 2)->default(0)->after('progression_ideal_cache');
        });

        Schema::table('realisation_competences', function (Blueprint $table) {
            $table->decimal('progression_ideal_cache', 5, 2)->default(0)->after('updated_at');
            $table->decimal('taux_rythme_cache', 5, 2)->default(0)->after('progression_ideal_cache');
        });

        Schema::table('realisation_modules', function (Blueprint $table) {
            $table->decimal('progression_ideal_cache', 5, 2)->default(0)->after('updated_at');
            $table->decimal('taux_rythme_cache', 5, 2)->default(0)->after('progression_ideal_cache');
        });
    }

    public function down()
    {
        Schema::table('realisation_uas', function (Blueprint $table) {
            $table->dropColumn(['progression_ideal_cache', 'taux_rythme_cache']);
        });

        Schema::table('realisation_micro_competences', function (Blueprint $table) {
            $table->dropColumn(['progression_ideal_cache', 'taux_rythme_cache']);
        });

        Schema::table('realisation_competences', function (Blueprint $table) {
            $table->dropColumn(['progression_ideal_cache', 'taux_rythme_cache']);
        });

        Schema::table('realisation_modules', function (Blueprint $table) {
            $table->dropColumn(['progression_ideal_cache', 'taux_rythme_cache']);
        });
    }
};
