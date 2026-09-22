<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisation_ua_projet_reponse_qcm', function (Blueprint $table) {
            $table->foreignId('realisation_ua_projet_id');

            $table->foreignId('reponse_qcm_id');

            $table->foreign(
                'realisation_ua_projet_id',
                'ruprq_realisation_ua_projet_fk'
            )
                ->references('id')
                ->on('realisation_ua_projets')
                ->onDelete('cascade');

            $table->foreign(
                'reponse_qcm_id',
                'ruprq_reponse_qcm_fk'
            )
                ->references('id')
                ->on('reponse_qcms')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisation_ua_projet_reponse_qcm');
    }
};