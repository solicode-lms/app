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
        Schema::table('realisation_ua_prototypes', function (Blueprint $table) {
            $table->index(['realisation_ua_id', 'note'], 'idx_rup_ua_note');
        });

        Schema::table('realisation_ua_projets', function (Blueprint $table) {
            $table->index(['realisation_ua_id', 'note'], 'idx_ruproj_ua_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realisation_ua_prototypes', function (Blueprint $table) {
            $table->dropIndex('idx_rup_ua_note');
        });

        Schema::table('realisation_ua_projets', function (Blueprint $table) {
            $table->dropIndex('idx_ruproj_ua_note');
        });
    }
};
