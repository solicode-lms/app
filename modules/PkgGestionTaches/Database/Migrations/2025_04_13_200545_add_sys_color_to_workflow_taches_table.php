<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('workflow_taches', function (Blueprint $table) {
            $table->foreignId('sys_color_id')
                  ->nullable()
                  ->after('reference') // ou un autre champ existant
                  ->constrained('sys_colors')
                  ->nullOnDelete(); // ou ->cascadeOnDelete() si souhaité
        });
    }

    public function down(): void
    {
        Schema::table('workflow_taches', function (Blueprint $table) {
            $table->dropForeign(['sys_color_id']);
            $table->dropColumn('sys_color_id');
        });
    }
};
