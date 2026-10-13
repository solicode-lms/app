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
        Schema::table('realisation_taches', function (Blueprint $table) {
            $table->unsignedInteger('cache_nombre_revisions')->default(0)->after('etat_realisation_tache_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realisation_taches', function (Blueprint $table) {
            $table->dropColumn('cache_nombre_revisions');
        });
    }
};
