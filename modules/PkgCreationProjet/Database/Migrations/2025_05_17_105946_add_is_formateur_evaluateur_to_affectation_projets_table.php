<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('affectation_projets', function (Blueprint $table) {
            // Ajout du booléen 'is_formateur_evaluateur', par défaut false
            $table->boolean('is_formateur_evaluateur')
                  ->default(true)
                  ->after('projet_id'); // ajustez 'role' selon la colonne de référence existante
        });
    }

    public function down()
    {
        Schema::table('affectation_projets', function (Blueprint $table) {
            $table->dropColumn('is_formateur_evaluateur');
        });
    }
};
