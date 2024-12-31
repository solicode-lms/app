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
        Schema::create('sys_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('model'); // Exemple : App\Models\Article
            $table->text('description')->nullable();
            $table->foreignId('module_id')->constrained('sys_modules')->onDelete('cascade'); // Clé étrangère vers sys_modules
            $table->foreignId('color_id')->nullable()->constrained('sys_colors')->onDelete('cascade');

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
        Schema::dropIfExists('sys_models');
    }
};
