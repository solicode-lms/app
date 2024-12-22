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
        Schema::create('sys_modules', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->string('name')->unique(); // Nom du module (doit être unique)
            $table->string('slug')->unique(); // Identifiant lisible et unique
            $table->text('description')->nullable(); // Description du module
            $table->boolean('is_active')->default(true); // Statut actif/inactif
            $table->integer('order')->default(0); // Ordre d'affichage ou de priorité
            $table->string('version')->nullable(); // Version du module
            $table->timestamps(); // Colonnes created_at et updated_at
            $table->softDeletes(); // Ajoute une colonne deleted_at pour suppression douce
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('{{ table }}');
    }
};
