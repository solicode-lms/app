<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_model_filters', function (Blueprint $table) {
            // Supprimer la clé étrangère pour pouvoir retirer l'index unique
            $table->dropForeign('user_model_filters_user_id_foreign');

            // Supprimer l'unique key
            $table->dropUnique('user_model_filters_user_id_model_name_unique');

            // Ajouter la nouvelle clé unique
            $table->unique(
                ['user_id', 'model_name', 'context_key'],
                'user_model_filters_user_id_model_name_context_key_unique'
            );

            // Recréer la clé étrangère
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('user_model_filters', function (Blueprint $table) {
            $table->dropUnique('user_model_filters_user_id_model_name_context_key_unique');
            $table->unique(['user_id', 'model_name'], 'user_model_filters_user_id_model_name_unique');
        });
    }
};
