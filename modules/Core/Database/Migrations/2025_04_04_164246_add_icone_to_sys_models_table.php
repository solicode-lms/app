<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sys_models', function (Blueprint $table) {
            $table->string('icone')->nullable()->after('reference'); // ou 'nom', selon la structure
        });
    }

    public function down(): void
    {
        Schema::table('sys_models', function (Blueprint $table) {
            $table->dropColumn('icone');
        });
    }
};
