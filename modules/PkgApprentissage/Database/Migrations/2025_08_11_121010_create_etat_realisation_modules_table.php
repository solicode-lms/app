<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('etat_realisation_modules', function (Blueprint $table) {
            $table->id();

            // Position d'affichage
            $table->integer('ordre')->default(0);

            // Référence UUID unique (règle SoliLMS)
            $table->string('reference')->unique();

            // Nom et description de l’état
            $table->string('nom');
            $table->longText('description')->nullable();

            // Couleur système pour affichage visuel
            $table->foreignId('sys_color_id')
                  ->nullable()
                  ->constrained('sys_colors')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('etat_realisation_modules');
    }
};
