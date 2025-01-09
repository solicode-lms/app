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
        Schema::create('has_many_relations', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('referenced_table'); // Nom de la table cible (entité enfant)
            $table->string('column_name'); // Nom de la colonne dans la table cible
            $table->longText('description')->nullable(); // Description optionnelle
            $table->foreignId('i_model_id') // Clé étrangère pour le modèle parent
                  ->constrained('i_models') // Définit la table cible
                  ->onDelete('cascade'); // Suppression en cascade
            $table->timestamps(); // Colonnes automatiques created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('has_many_relations');
    }
};
