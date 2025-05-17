<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('affectation_projet_evaluateur', function (Blueprint $table) {
            $table->id();

            // FK vers affectation_projets
            $table->foreignId('affectation_projet_id')
                  ->constrained('affectation_projets')
                  ->onDelete('cascade');

            // FK vers evaluateurs
            $table->foreignId('evaluateur_id')
                  ->constrained('evaluateurs')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('affectation_projet_evaluateur');
    }
};
