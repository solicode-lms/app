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
        Schema::create('validations', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->float('note', 5, 2)->nullable(); // Note avec deux décimales, ex: 18.75
            $table->string('message')->nullable(); // Message d’évaluation
            $table->boolean('is_valide')->default(false); // Indique si la validation est acceptée
            $table->foreignId('transfert_competence_id')->constrained('transfert_competences');
            $table->foreignId('realisation_projet_id')->constrained('realisation_projets');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('validations');
    }
};
