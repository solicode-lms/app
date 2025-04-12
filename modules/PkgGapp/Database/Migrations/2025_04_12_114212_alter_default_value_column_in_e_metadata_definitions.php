<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modifier la colonne 'default_value' en TEXT
        DB::statement("ALTER TABLE e_metadata_definitions MODIFY default_value TEXT NULL");
    }

    public function down(): void
    {
        // Revenir à VARCHAR(255) si besoin
        DB::statement("ALTER TABLE e_metadata_definitions MODIFY default_value VARCHAR(255) NULL");
    }
};
