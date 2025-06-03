<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('etat_evaluation_projets', function (Blueprint $table) {
            $table->id();

            // Code du statut (string)
            $table->string('code');

            // Titre (string)
            $table->string('titre');

            // Description (texte, facultatif)
            $table->text('description')->nullable();

            // Référence (string, unique)
            $table->string('reference')->unique();

            // Ordre d’affichage (integer, valeur par défaut 0)
            $table->integer('ordre')->default(0);

            // Clé étrangère vers la table sys_colors (nullable)
            $table->unsignedBigInteger('sys_color_id')->nullable();
            $table->foreign('sys_color_id')
                  ->references('id')
                  ->on('sys_colors')
                  ->onDelete('set null');

            // Horodatages Laravel (created_at, updated_at)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('etat_evaluation_realisation_projets');
    }
};
