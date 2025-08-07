<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
        public function up()
        {
        Schema::create('tache_affectations', function (Blueprint $table) {
        $table->id();

        // 🔗 Références
        $table->foreignId('tache_id')->constrained('taches')->onDelete('cascade');
        $table->foreignId('affectation_projet_id')->constrained('affectation_projets')->onDelete('cascade');

        // 🧮 Cache du pourcentage de réalisation sur cette tâche pour cette affectation (groupe ou sous-groupe)
        $table->double('pourcentage_realisation_cache')->default(0);

        // 🔁 Timestamps
        $table->timestamps();

        // 📌 Contrainte d’unicité : une tâche ne doit avoir qu’un seul enregistrement par affectation_projet
        $table->unique(['tache_id', 'affectation_projet_id']);

        // 🧭 Clé de référence standard SoliLMS
        $table->string('reference')->unique();
    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tache_affectations');
    }
};
