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
        Schema::create('meta_rule_attributes', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->string('name'); // Nom de l'attribut
            $table->longText('description')->nullable(); // Description longue
            $table->foreignId('type_meta_data_id') // Relation avec MetaRule
                  ->constrained('type_meta_data') // Définit la table cible
                  ->onDelete('cascade'); // Suppression en cascade
            $table->timestamps(); // Colonnes 'created_at' et 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_rule_attributes');
    }
};
