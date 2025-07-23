<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('phase_evaluations', function (Blueprint $table) {
            $table->id(); // id: int (primary key, auto-increment)
            
            // Colonne obligatoire 'reference'
            $table->string('reference')->unique();

            // Champs métiers
            $table->string('code')->unique();         // Ex: N1, N2, N3
            $table->string('libelle');               // Ex: Imiter, Adapter, Transposer
            $table->float('coefficient')->default(1); // Coefficient pondération
            $table->integer('ordre')->default(0);    // Ordre d'affichage
            $table->longText('description')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('phase_evaluations');
    }
};
