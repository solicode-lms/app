<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // Clé primaire

            $table->foreignId('user_id') // Clé étrangère vers 'users'
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('title'); // Titre court de la notification
            $table->longText('message'); // Contenu détaillé de la notification
            $table->boolean('is_read')->default(false); // Notification lue ou non
            $table->string('type')->nullable(); // Type d'événement (ex: tache, projet, autoformation)
            $table->json('data')->nullable(); // Données supplémentaires au format JSON (ex: id projet)

            $table->timestamp('sent_at')->nullable(); // Date d'envoi réelle
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
