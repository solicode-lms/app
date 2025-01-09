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
        Schema::create('i_models', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('name'); // Nom du modèle
            $table->string('icon')->nullable(); // Icône du modèle (nullable)
            $table->string('table_name'); // Nom de la table associée
            $table->longText('description')->nullable(); // Description longue
            $table->foreignId('i_package_id') // Relation avec IPackage
                  ->constrained('i_packages') // Définit la table cible
                  ->onDelete('cascade'); // Suppression en cascade
            $table->timestamps(); // Colonnes 'created_at' et 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('i_models');
    }
};
