<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('realisation_uas', function (Blueprint $table) {
            $table->dateTime('dernier_update')->nullable()->after('commentaire_formateur');
        });

        Schema::table('realisation_chapitres', function (Blueprint $table) {
            $table->dateTime('dernier_update')->nullable()->after('commentaire_formateur');
        });
    }

    public function down(): void
    {
        Schema::table('realisation_uas', function (Blueprint $table) {
            $table->dropColumn('dernier_update');
        });

        Schema::table('realisation_chapitres', function (Blueprint $table) {
            $table->dropColumn('dernier_update');
        });
    }
};
