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
        Schema::create('relationships', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->foreignId('source_model_id')->constrained('i_models')->onDelete('cascade'); // Modèle source
            $table->foreignId('target_model_id')->constrained('i_models')->onDelete('cascade'); // Modèle cible
            $table->string('type'); // Type de relation (e.g., ONE_TO_ONE, ONE_TO_MANY, etc.)
            $table->string('source_field'); // Champ source
            $table->string('target_field'); // Champ cible
            $table->boolean('cascade_on_delete')->default(false); // Cascade sur suppression
            $table->text('description')->nullable(); // Description facultative
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
        Schema::dropIfExists('relationships');
    }
};
