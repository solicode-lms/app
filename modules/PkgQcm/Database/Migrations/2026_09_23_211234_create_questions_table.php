<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->integer('ordre');
            $table->string('reference');
            $table->text('enonce');
            $table->string('type');
            $table->text('explication');
            $table->boolean('is_actif');
            $table->float('bareme');

            $table->timestamps();
           
            $table->foreignId('qcm_id')->constrained('qcms');
            $table->foreignId('unite_apprentissage_id')->constrained('unite_apprentissages');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
