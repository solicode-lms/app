<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('apprenant_groupe', function (Blueprint $table) {

            $table->timestamps();
           
            $table->foreignId('groupe_id')->constrained('groupes');
            $table->foreignId('apprenant_id')->constrained('apprenants');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apprenant_groupe');
    }
};
