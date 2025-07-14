<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('micro_competences', function (Blueprint $table) {
            $table->id();

            // Clé fonctionnelle
            $table->string('reference')->unique();

            // Champs métier
            $table->string('titre');
            $table->string('sous_titre')->nullable();
            $table->string('code')->unique();
            $table->string('lien')->nullable(); // lien vers support ou ressource externe
            $table->text('description')->nullable();
            $table->unsignedInteger('ordre')->default(0);

            // FK nullable avec suppression en cascade logique
            $table->foreignId('competence_id')
                  ->nullable()
                  ->constrained('competences')
                  ->nullOnDelete(); // équivaut à onDelete('set null')

            // Timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('micro_competences');
    }
};
