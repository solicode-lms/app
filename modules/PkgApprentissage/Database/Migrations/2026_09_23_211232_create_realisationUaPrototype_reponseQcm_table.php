<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Database\Migrations;

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
           
            $table->foreignId('')->constrained('realisationUaPrototypes');
            $table->foreignId('')->constrained('reponseQcms');

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
