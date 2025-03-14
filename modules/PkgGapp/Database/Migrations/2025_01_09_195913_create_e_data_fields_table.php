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
        Schema::create('e_data_fields', function (Blueprint $table) {
            $table->id(); 
            $table->string('reference')->unique();
            $table->string('name'); 
            $table->string('column_name');
            $table->string('data_type'); 
            $table->integer('field_order'); 
            $table->boolean('db_nullable'); 
            $table->boolean('db_primaryKey'); 
            $table->boolean('db_unique'); 
            $table->string('default_value')->nullable(); 
            $table->longText('description')->nullable(); 
            $table->foreignId('e_model_id')->constrained('e_models')->onDelete('cascade'); // Relation avec IModel
            $table->foreignId('e_relationship_id')->nullable()->constrained('e_relationships')->onDelete('cascade'); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('e_data_fields');
    }
};
