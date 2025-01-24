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
            $table->id();
            $table->string('reference')->unique();

            // Types supplémentaires ajoutés
            $table->boolean('value_boolean')->nullable(); 
            $table->string('value_string')->nullable(); 
            $table->integer('value_integer')->nullable(); 
            $table->float('value_float')->nullable(); // Nombre décimal
            $table->date('value_date')->nullable(); // Date
            $table->dateTime('value_datetime')->nullable(); // Date et heure
            $table->string('value_enum')->nullable(); // Enumération
            $table->json('value_json')->nullable(); // Données JSON
            $table->text('value_text')->nullable(); // Texte long

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
