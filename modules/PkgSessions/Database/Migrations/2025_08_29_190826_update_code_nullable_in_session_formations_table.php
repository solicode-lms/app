<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('session_formations', function (Blueprint $table) {
            // Supprimer la contrainte unique si elle existe
            $table->dropUnique(['code']); 
        });
    }

    public function down(): void
    {
        Schema::table('session_formations', function (Blueprint $table) {
            // Recréer l’unicité en cas de rollback
            $table->unique('code');
        });
    }
};
