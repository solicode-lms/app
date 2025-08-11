<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ajouter la colonne 'code' en nullable
        Schema::table('etat_realisation_modules', function (Blueprint $table) {
            if (!Schema::hasColumn('etat_realisation_modules', 'code')) {
                $table->string('code', 50)->nullable()->after('id');
            }
        });

        Schema::table('etat_realisation_competences', function (Blueprint $table) {
            if (!Schema::hasColumn('etat_realisation_competences', 'code')) {
                $table->string('code', 50)->nullable()->after('id');
            }
        });

        // 2. Remplir 'code' avec les valeurs existantes de 'reference'
        DB::table('etat_realisation_modules')
            ->whereNull('code')
            ->update(['code' => DB::raw('reference')]);

        DB::table('etat_realisation_competences')
            ->whereNull('code')
            ->update(['code' => DB::raw('reference')]);

        // 3. Modifier pour rendre la colonne obligatoire et unique
        Schema::table('etat_realisation_modules', function (Blueprint $table) {
            $table->string('code', 50)->unique()->nullable(false)->change();
        });

        Schema::table('etat_realisation_competences', function (Blueprint $table) {
            $table->string('code', 50)->unique()->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('etat_realisation_modules', function (Blueprint $table) {
            if (Schema::hasColumn('etat_realisation_modules', 'code')) {
                $table->dropColumn('code');
            }
        });

        Schema::table('etat_realisation_competences', function (Blueprint $table) {
            if (Schema::hasColumn('etat_realisation_competences', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};
