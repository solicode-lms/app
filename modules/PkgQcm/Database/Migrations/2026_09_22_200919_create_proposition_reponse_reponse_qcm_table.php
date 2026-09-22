<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposition_reponse_reponse_qcm', function (Blueprint $table) {
            $table->foreignId('proposition_reponse_id')
                ->constrained('proposition_reponses')
                ->onDelete('cascade');

            $table->foreignId('reponse_qcm_id')
                ->constrained('reponse_qcms')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposition_reponse_reponse_qcm');
    }
};