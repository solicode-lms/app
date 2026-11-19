<?php

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
        // 1. Modifier la colonne pour supprimer la valeur par défaut 50 et autoriser NULL par défaut
        Schema::table('tache_affectations', function (Blueprint $table) {
            $table->unsignedSmallInteger('seuil_live_coding')
                  ->nullable()
                  ->default(null)
                  ->change();
        });

        // 2. Réinitialiser à NULL les affectations de tâches ayant 50%
        // afin qu'elles se basent sur l'affectation de projet ou la tâche
        DB::table('tache_affectations')
            ->where('seuil_live_coding', 50)
            ->update(['seuil_live_coding' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('tache_affectations')
            ->whereNull('seuil_live_coding')
            ->update(['seuil_live_coding' => 50]);

        Schema::table('tache_affectations', function (Blueprint $table) {
            $table->unsignedSmallInteger('seuil_live_coding')
                  ->nullable()
                  ->default(50)
                  ->change();
        });
    }
};

