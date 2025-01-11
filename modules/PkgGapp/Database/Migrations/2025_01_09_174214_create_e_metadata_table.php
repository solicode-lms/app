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
        Schema::create('e_metadata', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->boolean('value_boolean')->nullable(); // Valeur booléenne
            $table->string('value_string')->nullable(); // Valeur chaîne
            $table->integer('value_int')->nullable(); // Valeur entière
            $table->json('value_object')->nullable(); // Valeur JSON
            $table->unsignedBigInteger('object_id'); // ID de l'objet lié (polymorphe)
            $table->string('object_type'); // Type de l'objet lié (polymorphe)
            $table->foreignId('e_metadata_definition_id')->constrained('e_metadata_definitions')->onDelete('cascade'); // Relation avec MetadataType
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
        Schema::dropIfExists('e_metadata');
    }
};
