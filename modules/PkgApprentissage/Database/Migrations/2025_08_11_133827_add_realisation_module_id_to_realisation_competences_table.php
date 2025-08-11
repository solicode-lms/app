<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('realisation_competences', function (Blueprint $table) {
            $table->foreignId('realisation_module_id')
                  ->after('apprenant_id')
                  ->constrained('realisation_modules')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('realisation_competences', function (Blueprint $table) {
            $table->dropForeign(['realisation_module_id']);
            $table->dropColumn('realisation_module_id');
        });
    }
};
