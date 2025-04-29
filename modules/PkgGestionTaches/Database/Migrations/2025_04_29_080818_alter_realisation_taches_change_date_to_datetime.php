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
            // Convertir les colonnes de DATE ➔ DATETIME
            $table->dateTime('dateDebut')->nullable()->change();
            $table->dateTime('dateFin')->nullable()->change();
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
            // Remettre les colonnes en DATE
            $table->date('dateDebut')->nullable()->change();
            $table->date('dateFin')->nullable()->change();
        });
    }
};
