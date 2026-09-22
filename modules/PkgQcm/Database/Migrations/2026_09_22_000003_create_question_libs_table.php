<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_libs', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->longText('enonce');
            $table->string('type');
            $table->longText('explication')->nullable();
            $table->boolean('is_actif')->default(true);

            // Relation ManyToOne → unite_apprentissages (PkgCompetences)
            $table->foreignId('unite_apprentissage_id')
                  ->nullable()
                  ->constrained('unite_apprentissages')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_libs');
    }
};
