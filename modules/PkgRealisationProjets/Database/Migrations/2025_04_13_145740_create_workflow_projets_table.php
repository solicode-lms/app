<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('workflow_projets', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique(); // ex: A_FAIRE, EN_COURS, TERMINE
            $table->string('titre'); // ex: "À faire"
            $table->longText('description')->nullable();

            $table->string('reference')->unique(); // référence unique pour Gapp
            $table->foreignId('sys_color_id')->constrained('sys_colors'); // couleur visuelle du statut

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_projets');
    }
};
