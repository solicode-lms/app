<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alignement_uas', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();  // Identifiant unique
            $table->text('description')->nullable();
            $table->integer('ordre')->default(0);

            // Relations
            $table->foreignId('unite_apprentissage_id')
                  ->constrained('unite_apprentissages')
                  ->onDelete('cascade');

            $table->foreignId('session_formation_id')
                  ->nullable()
                  ->constrained('session_formations')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alignement_uas');
    }
};
