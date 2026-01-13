<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taches', function (Blueprint $table) {
            $table->unsignedBigInteger('phase_projet_id')->nullable()->after('projet_id');
            $table->foreign('phase_projet_id')->references('id')->on('phase_projets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taches', function (Blueprint $table) {
            $table->dropForeign(['phase_projet_id']);
            $table->dropColumn('phase_projet_id');
        });
    }
};
