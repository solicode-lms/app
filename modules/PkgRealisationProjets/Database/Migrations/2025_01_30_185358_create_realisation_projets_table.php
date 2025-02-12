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
        Schema::create('realisation_projets', function (Blueprint $table) {
            $table->id();
            $table->date('date_debut');
            $table->date('date_fin')->nullable(); // Peut être NULL si le projet est en cours
            $table->text('rapport')->nullable(); // Peut être NULL avant la fin du projet
            $table->foreignId('etats_realisation_projet_id')->nullable()->constrained('etats_realisation_projets');
            $table->foreignId('apprenant_id')->constrained('apprenants');
            $table->foreignId('affectation_projet_id')->constrained('affectation_projets');
            $table->string('reference')->unique();
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
        Schema::dropIfExists('realisation_projets');
    }
};
