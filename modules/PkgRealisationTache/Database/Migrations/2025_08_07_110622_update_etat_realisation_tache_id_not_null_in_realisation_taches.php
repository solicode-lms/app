<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

 
return new class extends Migration
{
public function up(): void
{
    // ⚠️ Drop la foreign key via SQL brut (si Laravel ne la trouve pas)
    // DB::statement('ALTER TABLE realisation_taches DROP FOREIGN KEY realisation_taches_etat_realisation_tache_id_foreign');

    // Forcer une valeur par défaut (déjà fait chez toi apparemment)

    // Rendre NOT NULL
    Schema::table('realisation_taches', function (Blueprint $table) {
        $table->foreignId('etat_realisation_tache_id')
              ->nullable(false)
              ->change();
    });

    // Recréer la contrainte avec RESTRICT
    Schema::table('realisation_taches', function (Blueprint $table) {
        $table->foreign('etat_realisation_tache_id')
              ->references('id')->on('etat_realisation_taches')
              ->onDelete('restrict');
    });
}

    public function down(): void
    {
        Schema::table('realisation_taches', function (Blueprint $table) {
            $table->dropForeign(['etat_realisation_tache_id']);
            $table->foreignId('etat_realisation_tache_id')->nullable()->change();
            $table->foreign('etat_realisation_tache_id')
                  ->references('id')->on('etat_realisation_taches')
                  ->nullOnDelete(); // remettre l'ancien comportement
        });
    }
};
