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
        Schema::create('sys_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom de la couleur (e.g., primary, success)
            $table->string('hex', 7); // Code hexadécimal (e.g., #007bff)
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
        Schema::dropIfExists('sys_colors');
    }
};
