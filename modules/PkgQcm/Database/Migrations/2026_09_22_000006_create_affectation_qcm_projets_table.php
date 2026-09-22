<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affectation_qcm_projets', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();

            // Relation ManyToOne → affectation_projets (PkgRealisationProjets)
            $table->foreignId('affectation_projet_id')
                  ->constrained('affectation_projets')
                  ->onDelete('cascade');

            // Relation ManyToOne → qcms
            $table->foreignId('qcm_id')
                  ->constrained('qcms')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affectation_qcm_projets');
    }
};
