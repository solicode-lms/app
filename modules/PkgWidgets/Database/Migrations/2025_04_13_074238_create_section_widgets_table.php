<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('section_widgets', function (Blueprint $table) {
            $table->id();

            $table->string('titre'); // Titre principal de la section
            $table->string('sous_titre')->nullable(); // Sous-titre
            $table->string('icone')->nullable(); // Icône pour affichage
            $table->integer('ordre')->default(0); // Ordre d’affichage

            $table->string('reference')->unique(); // Référence unique pour Gapp
            $table->foreignId('sys_color_id')->nullable()->constrained('sys_colors')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_widgets');
    }
};
