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
        Schema::create('meta_models', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('name'); // Nom du meta-model
            $table->longText('description')->nullable(); // Description longue (nullable)
            $table->foreignId('type_meta_data_id')
            ->constrained('type_meta_data')
            ->onDelete('cascade');
            $table->foreignId('meta_rule_model_id') // Relation avec MetaData
                  ->constrained('meta_rule_models') // Définit la table cible
                  ->onDelete('cascade'); // Définit à NULL si supprimé
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
        Schema::dropIfExists('meta_models');
    }
};
