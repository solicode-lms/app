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
        Schema::create('dependance_taches', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('reference')->unique(); // Référence unique pour la dépendance
            
            // Relations
            $table->foreignId('tache_id')->constrained('taches')->onDelete('cascade'); // La tâche qui dépend d'une autre
            $table->foreignId('tache_cible_id')->constrained('taches')->onDelete('cascade'); // La tâche cible de la dépendance
            $table->foreignId('type_dependance_tache_id')->nullable()->constrained('type_dependance_taches')->onDelete('set null'); // Type de dépendance
            
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
        Schema::dropIfExists('dependance_taches');
    }
};
