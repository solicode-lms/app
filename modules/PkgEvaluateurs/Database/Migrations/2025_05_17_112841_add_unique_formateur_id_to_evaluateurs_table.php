<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('evaluateurs', function (Blueprint $table) {
            // Si formateur_id n'existe pas encore, le créer :
            if (! Schema::hasColumn('evaluateurs', 'formateur_id')) {
                $table->foreignId('formateur_id')
                      ->constrained('formateurs')
                      ->onDelete('cascade');
            }
            // Ajouter l'unicité sur formateur_id
            $table->unique('formateur_id');
        });
    }

    public function down()
    {
        Schema::table('evaluateurs', function (Blueprint $table) {
            $table->dropUnique(['formateur_id']);
            // Optionnel : si vous aviez créé la FK dans up(), la supprimer :
            // $table->dropForeign(['formateur_id']);
            // $table->dropColumn('formateur_id');
        });
    }
};
