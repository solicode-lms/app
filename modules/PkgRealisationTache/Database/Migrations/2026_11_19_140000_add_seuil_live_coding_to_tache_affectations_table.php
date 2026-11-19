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
        Schema::table('tache_affectations', function (Blueprint $table) {
            $table->unsignedSmallInteger('seuil_live_coding')
                  ->nullable()
                  ->default(50)
                  ->after('pourcentage_realisation_cache')
                  ->comment('Seuil effectif de réalisation pour déclencher le Live Coding');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tache_affectations', function (Blueprint $table) {
            $table->dropColumn('seuil_live_coding');
        });
    }
};

