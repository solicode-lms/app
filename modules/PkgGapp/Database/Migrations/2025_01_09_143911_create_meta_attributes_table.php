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
        Schema::create('meta_attributes', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('key'); // Clé de l'attribut
            $table->longText('value')->nullable(); // Valeur de l'attribut (nullable)
            $table->longText('description')->nullable(); // Description optionnelle
            $table->foreignId('type_meta_data_id')
                  ->constrained('type_meta_data')
                  ->onDelete('cascade');
            $table->foreignId('meta_rule_attribute_id')
                  ->constrained('meta_rule_attributes')
                  ->onDelete('cascade');
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
        Schema::dropIfExists('meta_attributes');
    }
};
