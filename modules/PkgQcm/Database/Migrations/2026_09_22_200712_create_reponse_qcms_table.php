<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('reponse_qcms', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('realisation_qcm_id')->constrained('realisation_qcms')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->dateTime('date_reponse');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('reponse_qcms');
    }
};