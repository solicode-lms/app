<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workflow_taches', function (Blueprint $table) {
            $table->integer('ordre')->nullable()->after('reference');
        });
    }

    public function down(): void
    {
        Schema::table('workflow_taches', function (Blueprint $table) {
            $table->dropColumn('ordre');
        });
    }
};
