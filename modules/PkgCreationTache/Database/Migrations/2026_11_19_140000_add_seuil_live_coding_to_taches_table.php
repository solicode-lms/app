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
        Schema::table('taches', function (Blueprint $table) {
            $table->unsignedSmallInteger('seuil_live_coding')
                  ->nullable()
                  ->default(50)
                  ->after('is_live_coding_task')
                  ->comment('Seuil de pourcentage de réalisation pour déclencher le Live Coding');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            $table->dropColumn('seuil_live_coding');
        });
    }
};

