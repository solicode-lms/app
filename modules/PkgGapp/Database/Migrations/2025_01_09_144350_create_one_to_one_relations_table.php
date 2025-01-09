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
        Schema::create('one_to_one_relations', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('source_table'); // Nom de la table source
            $table->string('source_column'); // Colonne dans la table source
            $table->string('target_table'); // Nom de la table cible
            $table->string('target_column'); // Colonne dans la table cible
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
        Schema::dropIfExists('one_to_one_relations');
    }
};
