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
        Schema::create('meta_rule_type_objet_attributs', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('name'); // Nom du type objet attribut
            $table->longText('description')->nullable(); // Description optionnelle
            $table->foreignId('meta_rule_attribute_id') // Clé étrangère vers MetaRule
                  ->nullable() 
                  ->constrained('meta_rule_attributes') // Relation avec table meta_rules
                  ->onDelete('set null'); // Suppression en cascade
            $table->foreignId('meta_rule_model_id') // Clé étrangère vers MetaRule
                    ->nullable()
                  ->constrained('meta_rule_models') // Relation avec table meta_rules
                  ->onDelete('set null'); // Suppression en cascade
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
        Schema::dropIfExists('meta_rule_type_objet_attributs');
    }
};
