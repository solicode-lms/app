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
        Schema::table('livrables', function (Blueprint $table) {
            $table->boolean('is_affichable_seulement_par_formateur')
                ->default(false)
                ->after('reference');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('livrables', function (Blueprint $table) {
            $table->dropColumn('is_affichable_seulement_par_formateur');
        });
    }
};
