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
        Schema::table('realisation_projets', function (Blueprint $table) {
            if (!Schema::hasColumn('realisation_projets', 'cache_date_premiere_soumission')) {
                $table->dateTime('cache_date_premiere_soumission')->nullable()->after('date_fin')->index();
            }
            if (!Schema::hasColumn('realisation_projets', 'cache_date_lancement')) {
                $table->dateTime('cache_date_lancement')->nullable()->after('cache_date_premiere_soumission')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realisation_projets', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('realisation_projets', 'cache_date_premiere_soumission')) {
                $columnsToDrop[] = 'cache_date_premiere_soumission';
            }
            if (Schema::hasColumn('realisation_projets', 'cache_date_lancement')) {
                $columnsToDrop[] = 'cache_date_lancement';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
