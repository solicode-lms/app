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
        Schema::create('label_realisation_taches', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('nom'); // Nom du label
            $table->longText('description')->nullable(); // Description optionnelle
            $table->string('reference')->unique(); // Référence unique pour le label
            
            // Relations
            $table->foreignId('formateur_id')->constrained('formateurs')->onDelete('cascade'); // Un label est créé par un formateur
            $table->foreignId('sys_color_id')->constrained('sys_colors'); // Un label est associé à une couleur du système
            
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
        Schema::dropIfExists('label_realisation_taches');
    }
};
