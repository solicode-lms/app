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
        Schema::create('e_relationships', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('type'); 
            $table->foreignId('source_model_id')->constrained('e_models')->onDelete('cascade'); // Modèle source
            $table->foreignId('target_model_id')->constrained('e_models')->onDelete('cascade'); // Modèle cible
           
            $table->boolean('cascade_on_delete')->default(false); // Cascade sur suppression
            $table->boolean('is_cascade')->default(false); // Cascade sur suppression
            $table->text('description')->nullable(); // Description facultative
            $table->string('column_name')->nullable(); // Nom de la colonne source
            $table->string('referenced_table')->nullable(); // Nom de la table cible
            $table->string('referenced_column')->nullable(); // Colonne cible
            $table->string('through')->nullable(); // Table pivot pour ManyToMany
            $table->string('with_column')->nullable(); // Colonne dans la table pivot
            $table->json('target_models_names')->nullable(); 
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
        Schema::dropIfExists('e_relationships');
    }
};
