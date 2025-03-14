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
        Schema::create('transfert_competences', function (Blueprint $table) {
            $table->id();
            $table->float('note')->nullable();
            $table->longText('question')->nullable();
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade'); // Relation avec Projet
            $table->foreignId('competence_id')->constrained('competences')->onDelete('restrict'); // Relation avec Competence
            $table->foreignId('niveau_difficulte_id')->constrained('niveau_difficultes'); // Relation avec Appreciation
            $table->string('reference')->unique();
            $table->timestamps(); // Champs created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfert_competences');
    }
};
