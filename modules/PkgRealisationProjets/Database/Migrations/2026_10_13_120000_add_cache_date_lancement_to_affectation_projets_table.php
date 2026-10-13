<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('affectation_projets', function (Blueprint $table) {
            if (!Schema::hasColumn('affectation_projets', 'cache_date_lancement')) {
                $table->dateTime('cache_date_lancement')->nullable()->after('date_fin')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affectation_projets', function (Blueprint $table) {
            if (Schema::hasColumn('affectation_projets', 'cache_date_lancement')) {
                $table->dropColumn('cache_date_lancement');
            }
        });
    }
};
