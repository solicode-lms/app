<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('critere_evaluations', function (Blueprint $table) {
            $table->foreignId('unite_apprentissage_id')
                  ->after('phase_evaluation_id')
                  ->constrained('unite_apprentissages')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('critere_evaluations', function (Blueprint $table) {
            $table->dropForeign(['unite_apprentissage_id']);
            $table->dropColumn('unite_apprentissage_id');
        });
    }
};
