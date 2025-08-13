<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('realisation_micro_competences', function (Blueprint $table) {
            // Ajout du champ lien du livrable
            $table->string('lien_livrable')
                  ->nullable()
                  ->after('etat_realisation_micro_competence_id')
                  ->comment('Lien vers le livrable de la micro-compétence');
        });
    }

    public function down()
    {
        Schema::table('realisation_micro_competences', function (Blueprint $table) {
            $table->dropColumn('lien_livrable');
        });
    }
};
