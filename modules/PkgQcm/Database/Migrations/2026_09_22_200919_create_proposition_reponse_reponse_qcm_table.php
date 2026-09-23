<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('proposition_reponse_reponse_qcm', function (Blueprint $table) {
            $table->foreignId('proposition_reponse_id');
            $table->foreignId('reponse_qcm_id');
            $table->foreign('proposition_reponse_id', 'prrq_proposition_reponse_fk')->references('id')->on('proposition_reponses')->onDelete('cascade');
            $table->foreign('reponse_qcm_id', 'prrq_reponse_qcm_fk')->references('id')->on('reponse_qcms')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('proposition_reponse_reponse_qcm');
    }
};