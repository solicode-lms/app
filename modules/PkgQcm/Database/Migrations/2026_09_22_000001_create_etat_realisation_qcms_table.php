<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etat_realisation_qcms', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('titre');
            $table->string('description')->nullable();
            $table->boolean('is_editable_by_formateur')->default(false);

            // Relation ManyToOne → sys_colors (Core)
            $table->foreignId('sys_color_id')
                  ->nullable()
                  ->constrained('sys_colors')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etat_realisation_qcms');
    }
};
