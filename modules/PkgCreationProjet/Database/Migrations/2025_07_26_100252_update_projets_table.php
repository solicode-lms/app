<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projets', function (Blueprint $table) {
            // Suppression de la colonne nombre_jour
            if (Schema::hasColumn('projets', 'nombre_jour')) {
                $table->dropColumn('nombre_jour');
            }

            // Ajout de la relation vers session_formations
            if (!Schema::hasColumn('projets', 'session_formation_id')) {
                $table->foreignId('session_formation_id')
                      ->nullable()
                      ->after('filiere_id')
                      ->constrained('session_formations')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('projets', function (Blueprint $table) {
            // Réajout de la colonne nombre_jour
            if (!Schema::hasColumn('projets', 'nombre_jour')) {
                $table->integer('nombre_jour')->default(0);
            }

            // Suppression de la clé étrangère vers session_formations
            if (Schema::hasColumn('projets', 'session_formation_id')) {
                $table->dropForeign(['session_formation_id']);
                $table->dropColumn('session_formation_id');
            }
        });
    }
};
