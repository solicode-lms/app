<?php
// TODO ce Table de pivot exsiste déja dans le PkgCreation Table 



namespace Modules\PkgCompetences\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('technology_transfertCompetence', function (Blueprint $table) {

        //     $table->timestamps();
           
        //     $table->foreignId('technology_id')->constrained('technologies');
        //     $table->foreignId('transfertCompetence_id')->constrained('transfertCompetences');

        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('technology_transfertCompetence');
    }
};
