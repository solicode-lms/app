<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('realisation_qcms', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('affectation_qcm_projet_id')->nullable()->constrained('affectation_qcm_projets')->onDelete('cascade');
            $table->foreignId('qcm_id')->nullable()->constrained('qcms')->onDelete('cascade');
            $table->foreignId('apprenant_id')->constrained('apprenants')->onDelete('cascade');
            $table->foreignId('etat_realisation_qcm_id')->nullable()->constrained('etat_realisation_qcms')->onDelete('set null');
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->dateTime('date_soumission')->nullable();
            $table->dateTime('date_validation')->nullable();
            $table->float('note_obtenu')->nullable();
            $table->string('statut')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('realisation_qcms');
    }
};