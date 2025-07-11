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
        Schema::table('realisation_taches', function (Blueprint $table) {
            $table->longText('remarques_formateur')->nullable()->after('etat_realisation_tache_id'); // Remarque du formateur
            $table->longText('remarques_apprenant')->nullable()->after('remarques_formateur'); // Remarque de l'apprenant
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('realisation_taches', function (Blueprint $table) {
            $table->dropColumn(['remarques_formateur', 'remarques_apprenant']);
        });
    }
};
