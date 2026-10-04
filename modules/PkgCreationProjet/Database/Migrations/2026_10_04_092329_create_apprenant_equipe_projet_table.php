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
        Schema::create('apprenant_equipe_projet', function (Blueprint $table) {
            $table->timestamps();
            $table->foreignId('equipe_projet_id')->constrained('equipe_projets')->onDelete('cascade');
            $table->foreignId('apprenant_id')->constrained('apprenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apprenant_equipe_projet');
    }
};
