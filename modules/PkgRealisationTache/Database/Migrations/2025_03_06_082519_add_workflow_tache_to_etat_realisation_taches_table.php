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
        Schema::table('etat_realisation_taches', function (Blueprint $table) {
            $table->foreignId('workflow_tache_id')
                  ->nullable()
                  ->constrained('workflow_taches')
                  ->onDelete('set null'); // Si le workflow est supprimé, la valeur devient NULL
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('etat_realisation_taches', function (Blueprint $table) {
            $table->dropForeign(['workflow_tache_id']);
            $table->dropColumn('workflow_tache_id');
        });
    }
};
