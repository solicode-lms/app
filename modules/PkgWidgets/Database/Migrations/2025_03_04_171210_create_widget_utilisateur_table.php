<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_utilisateur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Clé étrangère vers les utilisateurs
            $table->foreignId('widget_id')->constrained('widgets')->onDelete('cascade'); // Clé étrangère vers les widgets
            $table->integer('ordre')->default(0); // Ordre d'affichage du widget
            $table->string('titre'); // Titre personnalisé du widget
            $table->string('sous_titre')->nullable(); // Sous-titre optionnel
            $table->json('config')->nullable(); // Configuration spécifique de l'utilisateur pour ce widget
            $table->boolean('visible')->default(true); // Widget affiché ou masqué ?
            $table->timestamps(); // Champs created_at et updated_at
        });
    }

    /**
     * Annule les migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_utilisateur');
    }
};
