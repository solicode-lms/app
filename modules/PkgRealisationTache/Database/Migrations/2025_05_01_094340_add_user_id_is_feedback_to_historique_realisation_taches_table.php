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
        Schema::table('historique_realisation_taches', function (Blueprint $table) {
            // Ajout de la colonne user_id avec clé étrangère
            $table->foreignId('user_id')
                ->nullable()
                ->after('reference')
                ->constrained('users')
                ->onDelete('set null');

            // Ajout de la colonne isFeedback
            $table->boolean('isFeedback')
                ->default(false)
                ->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historique_realisation_taches', function (Blueprint $table) {
            // Suppression de la contrainte avant suppression de la colonne
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'isFeedback']);
        });
    }
};
