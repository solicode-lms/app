<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisation_qcms', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->dateTime('date_soumission')->nullable();
            $table->string('statut')->nullable();
            $table->dateTime('date_validation')->nullable();

            // Relation ManyToOne → affectation_qcm_projets
            $table->foreignId('affectation_qcm_projet_id')
                  ->constrained('affectation_qcm_projets')
                  ->onDelete('cascade');

            // Relation ManyToOne → etat_realisation_qcms
            $table->foreignId('etat_realisation_qcm_id')
                  ->nullable()
                  ->constrained('etat_realisation_qcms')
                  ->onDelete('set null');

            // Relation ManyToOne → qcms
            $table->foreignId('qcm_id')
                  ->constrained('qcms')
                  ->onDelete('cascade');

            // Relation ManyToOne → apprenants (PkgApprenants)
            $table->foreignId('apprenant_id')
                  ->constrained('apprenants')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisation_qcms');
    }
};
