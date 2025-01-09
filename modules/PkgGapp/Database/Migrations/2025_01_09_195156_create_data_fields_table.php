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
        Schema::create('data_fields', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('name'); // Nom du champ
            $table->foreignId('i_model_id')->constrained('i_models')->onDelete('cascade'); // Relation avec IModel
            $table->foreignId('field_type_id')->constrained('field_types')->onDelete('cascade'); // Relation avec FieldType
            $table->text('description')->nullable(); // Description du champ
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
        Schema::dropIfExists('data_fields');
    }
};
