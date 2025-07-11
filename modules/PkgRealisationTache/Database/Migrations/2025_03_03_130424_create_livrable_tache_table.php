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
        Schema::create('livrable_tache', function (Blueprint $table) {
            $table->foreignId('tache_id')->constrained('taches')->onDelete('cascade'); // Une tâche peut être liée à un livrable
            $table->foreignId('livrable_id')->constrained('livrables')->onDelete('cascade'); // Un livrable peut être lié à une tâche
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
        Schema::dropIfExists('livrable_tache');
    }
};
