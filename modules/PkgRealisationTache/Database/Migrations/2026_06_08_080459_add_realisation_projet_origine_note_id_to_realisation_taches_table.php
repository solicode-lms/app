<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('realisation_taches', function (Blueprint $table) {
            $table->foreignId('realisation_projet_origine_note_id')
                ->nullable()
                ->constrained('realisation_projets')
                ->onDelete('set null');
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
            $table->dropForeign(['realisation_projet_origine_note_id']);
            $table->dropColumn('realisation_projet_origine_note_id');
        });
    }
};
