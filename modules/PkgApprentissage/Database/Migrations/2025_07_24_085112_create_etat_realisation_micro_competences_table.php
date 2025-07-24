<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etat_realisation_micro_competences', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('nom');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_editable_only_by_formateur')->default(false);

            // Relation avec sys_colors (SET NULL)
            $table->foreignId('sys_color_id')
                  ->nullable()
                  ->constrained('sys_colors')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etat_realisation_micro_competences');
    }
};
