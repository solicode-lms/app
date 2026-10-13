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
            $table->timestamp('date_fin_live_coding_collectif')->nullable()->after('cache_date_lancement');
            $table->unsignedInteger('seuil_blocage_minutes')->default(10)->after('date_fin_live_coding_collectif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affectation_projets', function (Blueprint $table) {
            $table->dropColumn(['date_fin_live_coding_collectif', 'seuil_blocage_minutes']);
        });
    }
};
