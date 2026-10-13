<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('remediations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('realisation_tache_id')->constrained('realisation_taches')->onDelete('cascade');
            $table->foreignId('formateur_id')->constrained('formateurs')->onDelete('cascade');
            $table->string('type_remediation', 50)->default('INDIVIDUELLE');
            $table->text('diagnostic')->nullable();
            $table->boolean('est_resolu')->default(false);
            $table->string('reference')->unique();
            $table->timestamps();

            $table->index(['realisation_tache_id']);
            $table->index(['formateur_id']);
            $table->index(['type_remediation']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remediations');
    }
};
