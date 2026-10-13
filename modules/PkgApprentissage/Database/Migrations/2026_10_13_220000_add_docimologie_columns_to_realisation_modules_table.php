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
        Schema::table('realisation_modules', function (Blueprint $table) {
            $table->decimal('note_cc_officielle', 4, 2)->nullable()->after('bareme_non_evalue_cache');
            $table->decimal('note_efm_officielle', 4, 2)->nullable()->after('note_cc_officielle');
            $table->string('statut_docimologique', 30)->default('en_cours')->after('note_efm_officielle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('realisation_modules', function (Blueprint $table) {
            $table->dropColumn(['note_cc_officielle', 'note_efm_officielle', 'statut_docimologique']);
        });
    }
};
