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
        Schema::create('model_color', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sys_model_id')->constrained('sys_models')->onDelete('cascade');
            $table->foreignId('sys_color_id')->constrained('sys_colors')->onDelete('cascade');
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
        Schema::dropIfExists('model_color');
    }
};
