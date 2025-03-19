<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('nom')->unique(); // Nom unique de la formation
            $table->string('lien')->nullable(); // Lien vers une ressource externe
            $table->longText('description')->nullable(); // Description détaillée
            $table->boolean('is_officiel')->default(false); // Indique si c'est une formation officielle (Solicode) ou personnalisée
            $table->string('reference')->unique(); // Référence unique

            // Relations
            $table->foreignId('formateur_id')
                  ->nullable()
                  ->constrained('formateurs')
                  ->nullOnDelete(); // Suppression en cascade si le formateur est supprimé

            $table->foreignId('formation_officiel_id')
                  ->nullable()
                  ->constrained('formations')
                  ->nullOnDelete();

            $table->foreignId('competence_id')
                  ->nullable()
                  ->constrained('competences')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Annuler les migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formations');
    }
};
