<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livrable_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // Identifiant unique
            $table->string('titre');
            $table->text('description')->nullable();
            $table->integer('ordre')->default(0);

            // Relations
            $table->foreignId('session_formation_id')
                  ->constrained('session_formations')
                  ->onDelete('cascade');

            $table->foreignId('nature_livrable_id')
                  ->nullable()
                  ->constrained('nature_livrables')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livrable_sessions');
    }
};
