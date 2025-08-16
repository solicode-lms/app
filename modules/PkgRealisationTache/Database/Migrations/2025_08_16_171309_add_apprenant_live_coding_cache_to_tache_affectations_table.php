<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tache_affectations', function (Blueprint $table) {
            // Cache du live coding pour l'apprenant
            $table->json('apprenant_live_coding_cache')->nullable()
                  ->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('tache_affectations', function (Blueprint $table) {
            $table->dropColumn('apprenant_live_coding_cache');
        });
    }
};
