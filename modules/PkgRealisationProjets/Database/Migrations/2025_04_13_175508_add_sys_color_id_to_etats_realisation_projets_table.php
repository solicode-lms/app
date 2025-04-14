<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('etats_realisation_projets', function (Blueprint $table) {
            $table->foreignId('sys_color_id')->nullable()->after('reference')->constrained('sys_colors');
        });
    }

    public function down(): void
    {
        Schema::table('etats_realisation_projets', function (Blueprint $table) {
            $table->dropForeign(['sys_color_id']);
            $table->dropColumn('sys_color_id');
        });
    }
};
