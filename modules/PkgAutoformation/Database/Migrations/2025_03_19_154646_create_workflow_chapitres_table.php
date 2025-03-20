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
        Schema::create('workflow_chapitres', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('code')->unique(); 
            $table->string('titre')->unique(); 
            $table->foreignId('sys_color_id')->constrained('sys_colors'); // Clé étrangère vers sys_colors
            $table->longText('description')->nullable(); 
            $table->string('reference')->unique(); // Référence unique du workflow
            $table->timestamps(); // Champs created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workflow_chapitres');
    }
};
