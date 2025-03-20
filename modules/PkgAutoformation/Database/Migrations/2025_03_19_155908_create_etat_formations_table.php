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
            $table->string('nom')->unique(); // Nom de l'état
            $table->boolean('is_editable_only_by_formateur')->nullable()->default(false); // Restriction d'édition, nullable
            $table->longText('description')->nullable(); // Description détaillée
            $table->string('reference')->unique(); // Référence unique
            $table->foreignId('workflow_formation_id')
                  ->nullable() // Rendre la colonne nullable pour permettre SET NULL
                  ->constrained('workflow_formations')
                  ->nullOnDelete(); // Supprime la relation mais garde l'entrée
            $table->foreignId('formateur_id')->constrained('formateurs')->onDelete('cascade'); // Clé étrangère vers formateurs
            $table->foreignId('sys_color_id')->constrained('sys_colors'); // Clé étrangère vers sys_colors
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
