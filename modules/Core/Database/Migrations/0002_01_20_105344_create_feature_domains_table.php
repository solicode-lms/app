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
        Schema::create('feature_domains', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->string('name')->unique(); // Nom unique du domaine (ex: Gestion des utilisateurs)
            $table->string('slug')->unique(); // Identifiant lisible (ex: gestion-utilisateurs)
            $table->longText('description')->nullable(); // Description détaillée
            $table->foreignId('sys_module_id')->constrained('sys_modules')->onDelete('cascade'); // Lien avec SysModule
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
        Schema::dropIfExists('feature_domains');
    }
};
