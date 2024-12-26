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
        Schema::create('livrables', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('titre'); // Titre du livrable
            $table->text('description')->nullable(); // Description du livrable (optionnelle)
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade'); // Relation avec Projet
            $table->foreignId('nature_livrable_id')->constrained('nature_livrables')->onDelete('restrict'); // Relation avec Nature de Livrable
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
        Schema::dropIfExists('livrables');
    }
};
