<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('etats_realisation_projets', function (Blueprint $table) {
            $table->foreignId('workflow_projet_id')
                  ->nullable()
                  ->after('reference') // ou après 'description', selon ton besoin
                  ->constrained('workflow_projets')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('etats_realisation_projets', function (Blueprint $table) {
            $table->dropForeign(['workflow_projet_id']);
            $table->dropColumn('workflow_projet_id');
        });
    }
};
