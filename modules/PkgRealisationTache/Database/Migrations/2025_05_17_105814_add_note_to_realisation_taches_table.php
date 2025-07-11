<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('realisation_taches', function (Blueprint $table) {
            // Ajout de la colonne 'note' de type float, nullable
            $table->float('note')->nullable()->after('tache_id');
        });
    }

    public function down()
    {
        Schema::table('realisation_taches', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
};
