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
        Schema::create('realisationUaPrototype_reponseQcm', function (Blueprint $table) {

            $table->timestamps();
           
            $table->foreignId('')->constrained('reponseQcms');
            $table->foreignId('')->constrained('realisationUaPrototypes');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realisationUaPrototype_reponseQcm');
    }
};
