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
        Schema::create('etat_chapitres', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('code')->unique(); // Code unique de l'état
            $table->string('nom')->unique(); // Nom de l'état
            $table->string('reference')->unique(); // Référence unique
            $table->foreignId('workflow_chapitre_id') // Clé étrangère vers workflow_chapitres
                  ->nullable()
                  ->constrained('workflow_chapitres')
                  ->nullOnDelete();
            $table->longText('description')->nullable(); // Description détaillée
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
        Schema::dropIfExists('etat_chapitres');
    }
};
