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
        Schema::create('sys_controller', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->foreignId('module_id')->constrained('sys_modules')->onDelete('cascade'); // Clé étrangère vers sys_modules
            $table->string('name')->unique(); // Nom unique du contrôleur
            $table->string('slug')->unique(); // Slug unique pour le contrôleur
            $table->text('description')->nullable(); // Description du contrôleur
            $table->boolean('is_active')->default(true); // Statut actif/inactif
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
        Schema::dropIfExists('sys_controller');
    }
};
