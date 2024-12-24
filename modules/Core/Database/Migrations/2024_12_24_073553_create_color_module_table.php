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
        Schema::create('color_module', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->foreignId('sys_color_id')->constrained('sys_colors')->onDelete('cascade'); // Clé étrangère vers sys_colors
            $table->foreignId('sys_module_id')->constrained('sys_modules')->onDelete('cascade'); // Clé étrangère vers sys_modules
            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('color_module');
    }
};
