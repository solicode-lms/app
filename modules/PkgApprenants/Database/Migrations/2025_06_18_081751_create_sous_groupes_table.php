<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sous_groupes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // Obligatoire dans SoliLMS
            $table->string('nom');
            $table->longText('description')->nullable();

            $table->foreignId('groupe_id')
                  ->constrained('groupes')
                  ->onDelete('cascade'); // Un sous-groupe appartient à un groupe

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sous_groupes');
    }
};
