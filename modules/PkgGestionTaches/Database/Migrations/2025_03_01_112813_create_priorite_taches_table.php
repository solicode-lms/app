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
        Schema::create('priorite_taches', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('nom'); // Nom de la priorité
            $table->integer('ordre'); // Ordre de priorité
            $table->longText('description')->nullable(); // Description optionnelle
            $table->string('reference')->unique(); // Référence unique pour la priorité

            // Relations
            $table->foreignId('formateur_id')->constrained('formateurs')->onDelete('cascade'); // Une priorité est définie par un formateur
            
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
        Schema::dropIfExists('priorite_taches');
    }
};
