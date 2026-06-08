<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('taches', function (Blueprint $table) {
            $table->foreignId('projet_origine_note_id')
                ->nullable()
                ->constrained('projets')
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
        Schema::table('taches', function (Blueprint $table) {
            $table->dropForeign(['projet_origine_note_id']);
            $table->dropColumn('projet_origine_note_id');
        });
    }
};
