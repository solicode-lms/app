<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            $table->boolean('is_live_coding_task')
                  ->default(true)
                  ->after('note')
                  ->comment('Indique si la tâche fait partie du live coding des apprenants');
        });
    }

    public function down(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            $table->dropColumn('is_live_coding_task');
        });
    }
};
