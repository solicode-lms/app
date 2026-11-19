<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('affectation_projets', function (Blueprint $table) {
            $table->unsignedSmallInteger('seuil_live_coding')
                  ->default(50)
                  ->after('seuil_blocage_minutes')
                  ->comment('Seuil par défaut de pourcentage de réalisation pour déclencher le Live Coding');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affectation_projets', function (Blueprint $table) {
            $table->dropColumn('seuil_live_coding');
        });
    }
};

