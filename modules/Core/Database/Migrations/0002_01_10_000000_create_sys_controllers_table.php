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
        Schema::create('sys_controllers', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->foreignId('sys_module_id')->constrained('sys_modules')->onDelete('cascade'); // Clé étrangère vers sys_modules
            $table->string('name')->unique(); // Nom unique du contrôleur
            $table->string('slug')->unique(); // Slug unique pour le contrôleur
            $table->longText('description')->nullable(); // Description du contrôleur
            $table->boolean('is_active')->default(true); // Statut actif/inactif
            $table->string('reference')->unique();
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
