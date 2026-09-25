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
        Schema::table('realisation_ua_prototypes', function (Blueprint $table) {
            $table->float('note_qcm')->nullable();
            $table->float('barem_qcm')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('realisation_ua_prototypes', function (Blueprint $table) {
            $table->dropColumn(['note_qcm', 'barem_qcm']);
        });
    }
};
