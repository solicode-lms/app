<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('evaluateurs', function (Blueprint $table) {
            // Colonne user_id non signée (unsigned) pour clé étrangère
            $table->unsignedBigInteger('user_id')->after('reference');

            // Contrainte de clé étrangère vers users.id
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->nullable()
                  ->onDelete('cascade');

                  
        });

         Schema::table('evaluateurs', function (Blueprint $table) {
            // 1. Supprimer la contrainte de clé étrangère
            $table->dropForeign(['formateur_id']);
            // 2. Supprimer la colonne formateur_id
            $table->dropColumn('formateur_id');
        });
    }

    public function down()
    {
        Schema::table('evaluateurs', function (Blueprint $table) {
            // Suppression de la contrainte FK puis de la colonne
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
