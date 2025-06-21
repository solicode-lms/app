<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affectation_projets', function (Blueprint $table) {
            // barème à utiliser pour arrondir la note finale ; nullable par défaut
            $table->unsignedInteger('bareme_arrondi')
                  ->nullable()
                  ->after('description')
                  ->comment('Barème pour arrondir la note finale des réalisations de projet');
        });
    }

    public function down(): void
    {
        Schema::table('affectation_projets', function (Blueprint $table) {
            $table->dropColumn('bareme_arrondi');
        });
    }
};
