<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Rendre les colonnes nullable
        DB::statement("ALTER TABLE mobilisation_uas 
            MODIFY bareme_evaluation_prototype DOUBLE NULL DEFAULT NULL");

        DB::statement("ALTER TABLE mobilisation_uas 
            MODIFY bareme_evaluation_projet DOUBLE NULL DEFAULT NULL");
    }

    public function down(): void
    {
        // Revenir à NOT NULL DEFAULT 0
        DB::statement("ALTER TABLE mobilisation_uas 
            MODIFY bareme_evaluation_prototype DOUBLE NOT NULL DEFAULT 0");

        DB::statement("ALTER TABLE mobilisation_uas 
            MODIFY bareme_evaluation_projet DOUBLE NOT NULL DEFAULT 0");
    }
};
