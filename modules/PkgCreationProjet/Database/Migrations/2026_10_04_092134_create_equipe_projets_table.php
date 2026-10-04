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
        Schema::create('equipe_projets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('projet_id');
            $table->string('nom');
            $table->string('reference')->unique();
            $table->timestamps();

            $table->foreign('projet_id')->references('id')->on('projets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipe_projets');
    }
};
