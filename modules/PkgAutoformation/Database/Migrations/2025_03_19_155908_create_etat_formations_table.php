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
        Schema::create('etat_formations', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('code')->unique(); // Code unique de l'état
            $table->string('nom')->unique(); // Nom de l'état
            $table->longText('description')->nullable(); // Description détaillée
            $table->string('reference')->unique(); // Référence unique
            $table->foreignId('workflow_formation_id')
                  ->nullable() // Rendre la colonne nullable pour permettre SET NULL
                  ->constrained('workflow_formations')
                  ->nullOnDelete(); // Supprime la relation mais garde l'entrée
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
        Schema::dropIfExists('etat_formations');
    }
};
