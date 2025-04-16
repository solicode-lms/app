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
        Schema::create('user_model_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('model_name'); // Exemple : 'RealisationTache'
            $table->json('filters')->nullable(); // JSON avec les filtres appliqués
            $table->timestamps();
        
            $table->unique(['user_id', 'model_name']); // Un seul filtre par modèle et utilisateur
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_model_filters');
    }
};
