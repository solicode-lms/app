<?php


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
        Schema::create('niveau_competences', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->longText('description')->nullable();

            $table->timestamps();
           
            $table->foreignId('competence_id')->constrained('competences');
            $table->string('reference')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niveau_competences');
    }
};
