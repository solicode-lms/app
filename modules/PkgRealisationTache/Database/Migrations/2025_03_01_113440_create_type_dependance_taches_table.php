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
        Schema::create('type_dependance_taches', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('titre'); // Titre du type de dépendance
            $table->longText('description')->nullable(); // Description optionnelle
            $table->string('reference')->unique(); // Référence unique pour le type de dépendance
            $table->timestamps(); // Champs created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_dependance_taches');
    }
};
