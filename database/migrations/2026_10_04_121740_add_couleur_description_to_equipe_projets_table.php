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
        Schema::table('equipe_projets', function (Blueprint $table) {
            $table->foreignId('sys_color_id')->nullable()->after('nom')->constrained('sys_colors');
            $table->longText('description')->nullable()->after('sys_color_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipe_projets', function (Blueprint $table) {
            $table->dropForeign(['sys_color_id']);
            $table->dropColumn(['sys_color_id', 'description']);
        });
    }
};
