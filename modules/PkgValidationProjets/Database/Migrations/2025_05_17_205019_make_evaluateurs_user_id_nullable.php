<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('evaluateurs', function (Blueprint $table) {
            // 1. Supprimer la contrainte FK existante
            $table->dropForeign(['user_id']);

            // 2. Rendre la colonne nullable
            $table->unsignedBigInteger('user_id')
                  ->nullable()
                  ->change();

            // 3. Ré-ajouter la contrainte FK vers users(id)
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('evaluateurs', function (Blueprint $table) {
            // 1. Supprimer la contrainte FK
            $table->dropForeign(['user_id']);

            // 2. Revenir à non-nullable
            $table->unsignedBigInteger('user_id')
                  ->nullable(false)
                  ->change();

            // 3. Ré-ajouter la contrainte FK
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
