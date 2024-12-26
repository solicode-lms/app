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
            $table->id(); // Identifiant unique
            $table->text("description")->nullable();
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade'); // Relation avec Projet
            $table->foreignId('competence_id')->constrained('competences')->onDelete('restrict'); // Relation avec Competence
            $table->foreignId('appreciation_id')->constrained('appreciations'); // Relation avec Appreciation
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
