<?php

namespace Modules\PkgFormation\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Étape 1 : Ajouter la colonne sans la contrainte unique
        Schema::table('modules', function (Blueprint $table) {
            $table->string('code')->nullable()->after('id'); 
        });

    

         // Étape 2 : Remplir les anciennes lignes avec un code généré
         DB::table('modules')->get()->each(function ($module) {
            $generatedCode = 'MOD-' . str_pad($module->id, 5, '0', STR_PAD_LEFT) . '-' . substr(md5(uniqid()), 0, 6);
            DB::table('modules')
                ->where('id', $module->id)
                ->update(['code' => $generatedCode]);
        });
        

        // Étape 3 : Rendre la colonne unique
        Schema::table('modules', function (Blueprint $table) {
            $table->string('code')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
