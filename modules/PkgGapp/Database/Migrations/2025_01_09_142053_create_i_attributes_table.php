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
        Schema::create('i_attributes', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('name'); // Nom de l'attribut
            $table->string('column_name'); // Nom de la colonne associée
            $table->longText('description')->nullable(); // Description longue
            $table->foreignId('i_model_id') // Relation avec IModel
                  ->constrained('i_models') // Définit la table cible
                  ->onDelete('cascade'); // Suppression en cascade
            $table->foreignId('type_attribute_id') // Relation avec TypeAttribute
                  ->nullable()
                  ->constrained('type_attributes') // Définit la table cible
                  ->onDelete('set null'); // Définit à NULL si supprimé
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
        Schema::dropIfExists('i_attributes');
    }
};
