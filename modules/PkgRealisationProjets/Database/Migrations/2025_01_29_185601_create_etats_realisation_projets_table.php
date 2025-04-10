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
        Schema::create('etats_realisation_projets', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->longText('description')->nullable();
            $table->foreignId('formateur_id')->constrained('formateurs')->onDelete('cascade');
            $table->string('reference')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etats_realisation_projet');
    }
};
