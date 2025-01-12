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
            $table->string('code');
            $table->string('name'); 
            $table->string('column');
            $table->string('dataType'); 
            $table->string('defaultValue')->nullable(); 
            $table->text('description')->nullable(); 
            $table->foreignId('e_model_id')->constrained('e_models')->onDelete('cascade'); // Relation avec IModel
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
