<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('unite_apprentissages', function (Blueprint $table) {
            $table->id();

            $table->string('reference')->unique();
            $table->string('nom');
            $table->string('lien')->nullable(); // support, lien vers ressource
            $table->text('description')->nullable();
            $table->unsignedInteger('ordre')->default(0);
         
            // FK avec suppression en cascade
            $table->foreignId('micro_competence_id')
                  ->constrained('micro_competences')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unite_apprentissages');
    }
};
