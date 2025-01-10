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
        Schema::create('metadata_types', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->string('name'); // Nom lisible
            $table->string('code')->unique(); // Code unique pour identification technique
            $table->string('type'); // Stocke le type comme une chaîne
            $table->string('scope'); // Stocke la portée comme une chaîne
            $table->string('description')->nullable(); // Description facultative
            $table->string('default_value')->nullable(); // Valeur par défaut
            $table->text('validation_rules')->nullable(); // Règles de validation
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
        Schema::dropIfExists('metadata_types');
    }
};
