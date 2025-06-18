<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affectation_projets', function (Blueprint $table) {
            // Rendre le groupe_id nullable
            $table->unsignedBigInteger('groupe_id')->nullable()->change();

            // Ajouter la relation vers sous_groupes (nullable)
            $table->foreignId('sous_groupe_id')
                ->nullable()
                ->after('groupe_id')
                ->constrained('sous_groupes')
                ->nullOnDelete(); // ou ->onDelete('set null') pour MySQL < 8.0
        });
    }

    public function down(): void
    {
        Schema::table('affectation_projets', function (Blueprint $table) {
            $table->dropForeign(['sous_groupe_id']);
            $table->dropColumn('sous_groupe_id');

            // Revenir à NOT NULL si besoin (attention, nécessite une valeur par défaut ou données valides)
            // $table->unsignedBigInteger('groupe_id')->nullable(false)->change(); // ⚠️ à utiliser avec prudence
        });
    }
};
