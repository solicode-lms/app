<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('realisation_uas', function (Blueprint $table) {
            $table->double('note_cc_cache')->nullable()->after('bareme_non_evalue_cache');
            $table->double('bareme_cc_cache')->nullable()->after('note_cc_cache');
        });
    }

    public function down(): void
    {
        Schema::table('realisation_uas', function (Blueprint $table) {
            $table->dropColumn(['note_cc_cache', 'bareme_cc_cache']);
        });
    }
};
