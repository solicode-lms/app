<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('formation_technology', function (Blueprint $table) {
            $table->foreignId('formation_id')->constrained('formations')->onDelete('cascade');
            $table->foreignId('technology_id')->constrained('technologies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('formation_technology');
    }
};
