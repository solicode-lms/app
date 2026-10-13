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
            $table->timestamp('cache_date_entree_file')->nullable()->index()->after('cache_nombre_revisions');
            $table->timestamp('cache_date_debut_live_coding')->nullable()->after('cache_date_entree_file');
            $table->unsignedInteger('cache_delai_attente_minutes')->nullable()->after('cache_date_debut_live_coding');
            $table->string('cache_session_live_coding_id')->nullable()->index()->after('cache_delai_attente_minutes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realisation_taches', function (Blueprint $table) {
            $table->dropIndex(['cache_date_entree_file']);
            $table->dropIndex(['cache_session_live_coding_id']);
            $table->dropColumn([
                'cache_date_entree_file',
                'cache_date_debut_live_coding',
                'cache_delai_attente_minutes',
                'cache_session_live_coding_id'
            ]);
        });
    }
};
