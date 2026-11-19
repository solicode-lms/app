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
        Schema::table('taches', function (Blueprint $table) {
            $table->unsignedSmallInteger('seuil_live_coding')
                  ->nullable()
                  ->default(null)
                  ->change();
        });

        // 2. Réinitialiser à NULL toutes les tâches ayant actuellement 50%
        // afin qu'elles héritent dynamiquement du seuil défini sur l'affectation du projet
        DB::table('taches')
            ->where('seuil_live_coding', 50)
            ->update(['seuil_live_coding' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('taches')
            ->whereNull('seuil_live_coding')
            ->update(['seuil_live_coding' => 50]);

        Schema::table('taches', function (Blueprint $table) {
            $table->unsignedSmallInteger('seuil_live_coding')
                  ->nullable()
                  ->default(50)
                  ->change();
        });
    }
};

