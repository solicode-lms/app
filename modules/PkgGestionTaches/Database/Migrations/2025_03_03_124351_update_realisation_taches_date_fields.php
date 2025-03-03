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
            $table->date('dateDebut')->nullable()->change(); // Convertir DateTime en Date
            $table->date('dateFin')->nullable()->change(); // Convertir DateTime en Date
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
            $table->dateTime('dateDebut')->nullable()->change(); // Revenir à DateTime
            $table->dateTime('dateFin')->nullable()->change(); // Revenir à DateTime
        });
    }
};
