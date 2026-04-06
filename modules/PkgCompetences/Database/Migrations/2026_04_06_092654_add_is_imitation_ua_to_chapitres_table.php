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
        Schema::table('chapitres', function (Blueprint $table) {
            $table->boolean('is_imitation_ua')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chapitres', function (Blueprint $table) {
            $table->dropColumn('is_imitation_ua');
        });
    }
};
