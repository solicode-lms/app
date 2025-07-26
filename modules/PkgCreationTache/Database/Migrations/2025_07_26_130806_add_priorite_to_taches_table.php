<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            if (!Schema::hasColumn('taches', 'priorite')) {
               $table->integer('priorite')
                ->nullable()
                ->after('ordre')
                ->comment('Niveau de priorité de la tâche, peut être NULL');
            }
        });
    }

    public function down(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            if (Schema::hasColumn('taches', 'priorite')) {
                $table->dropColumn('priorite');
            }
        });
    }
};
