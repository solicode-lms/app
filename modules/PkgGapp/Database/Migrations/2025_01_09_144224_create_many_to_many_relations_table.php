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
        Schema::create('many_to_many_relations', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('table_name'); // Nom de la table pivot pour la relation
            $table->string('source_table'); // Nom de la table source
            $table->string('source_column'); // Colonne source dans la table source
            $table->string('target_table'); // Nom de la table cible
            $table->string('target_column'); // Colonne cible dans la table cible
            $table->longText('description')->nullable(); // Description optionnelle
            $table->foreignId('i_model_id') // Relation avec IModel
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
        Schema::dropIfExists('many_to_many_relations');
    }
};
