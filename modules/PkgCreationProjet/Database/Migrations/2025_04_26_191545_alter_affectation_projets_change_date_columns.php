<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 🔥 Modifier la colonne en DATETIME via du SQL brut
        DB::statement('ALTER TABLE affectation_projets MODIFY date_debut DATETIME NOT NULL');
        DB::statement('ALTER TABLE affectation_projets MODIFY date_fin DATETIME NULL');
    }

    public function down(): void
    {
        // 🔙 Revenir en arrière proprement
        DB::statement('ALTER TABLE affectation_projets MODIFY date_debut DATE NOT NULL');
        DB::statement('ALTER TABLE affectation_projets MODIFY date_fin DATE NULL');
    }
};
