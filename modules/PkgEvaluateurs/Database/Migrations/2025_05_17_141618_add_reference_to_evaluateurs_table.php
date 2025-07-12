<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('evaluateurs', function (Blueprint $table) {
            // Ajout d'une colonne 'reference' juste après l'ID
            $table->string('reference')->unique()->after('id');
        });
    }

    public function down()
    {
        Schema::table('evaluateurs', function (Blueprint $table) {
            // Suppression de la contrainte unique puis de la colonne
            $table->dropUnique(['reference']);
            $table->dropColumn('reference');
        });
    }
};
