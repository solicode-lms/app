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
        Schema::table('affectation_qcm_projets', function (Blueprint $table) {
            $table->boolean('saise_automatique_note_qcm')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('affectation_qcm_projets', function (Blueprint $table) {
            $table->dropColumn('saise_automatique_note_qcm');
        });
    }
};
