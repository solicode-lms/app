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
        Schema::create('many_to_one_relations', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('column'); // Nom de la colonne dans la table source
            $table->string('referenced_table'); // Nom de la table cible
            $table->string('referenced_column'); // Colonne cible (généralement 'id')
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
        Schema::dropIfExists('many_to_one_relations');
    }
};
