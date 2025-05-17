<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluation_realisation_taches', function (Blueprint $table) {
            $table->id();
            $table->float('note');
            $table->text('message')->nullable();
            // FK vers evaluateurs
            $table->foreignId('evaluateur_id')
                  ->constrained('evaluateurs')
                  ->onDelete('cascade');
            // FK vers realisation_taches
            $table->foreignId('realisation_tache_id')
                  ->constrained('realisation_taches')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_realisation_taches');
    }
};
