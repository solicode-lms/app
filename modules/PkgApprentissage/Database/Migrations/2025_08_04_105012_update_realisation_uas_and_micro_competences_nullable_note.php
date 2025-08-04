<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Étapes pour realisation_uas
        Schema::table('realisation_uas', function (Blueprint $table) {
            $table->double('note_cache_tmp')->nullable()->after('progression_cache');
            $table->double('bareme_cache_tmp')->nullable()->after('note_cache_tmp');
        });

        DB::statement('UPDATE realisation_uas SET note_cache_tmp = note_cache, bareme_cache_tmp = bareme_cache');

        Schema::table('realisation_uas', function (Blueprint $table) {
            $table->dropColumn(['note_cache', 'bareme_cache']);
        });

        Schema::table('realisation_uas', function (Blueprint $table) {
            $table->renameColumn('note_cache_tmp', 'note_cache');
            $table->renameColumn('bareme_cache_tmp', 'bareme_cache');
        });

        // Étapes pour realisation_micro_competences
        Schema::table('realisation_micro_competences', function (Blueprint $table) {
            $table->double('note_cache_tmp')->nullable()->after('progression_cache');
            $table->double('bareme_cache_tmp')->nullable()->after('note_cache_tmp');
        });

        DB::statement('UPDATE realisation_micro_competences SET note_cache_tmp = note_cache, bareme_cache_tmp = bareme_cache');

        Schema::table('realisation_micro_competences', function (Blueprint $table) {
            $table->dropColumn(['note_cache', 'bareme_cache']);
        });

        Schema::table('realisation_micro_competences', function (Blueprint $table) {
            $table->renameColumn('note_cache_tmp', 'note_cache');
            $table->renameColumn('bareme_cache_tmp', 'bareme_cache');
        });
    }

    public function down(): void
    {
        // rollback = recréer en NOT NULL par défaut
        Schema::table('realisation_uas', function (Blueprint $table) {
            $table->double('note_cache')->default(0)->after('progression_cache');
            $table->double('bareme_cache')->default(0)->after('note_cache');
        });

        Schema::table('realisation_micro_competences', function (Blueprint $table) {
            $table->double('note_cache')->default(0)->after('progression_cache');
            $table->double('bareme_cache')->default(0)->after('note_cache');
        });
    }
};
