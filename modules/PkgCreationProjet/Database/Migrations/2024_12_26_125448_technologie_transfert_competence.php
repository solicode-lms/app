<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technologie_transfert_competence', function (Blueprint $table) {
            $table->foreignId('technologie_id')->constrained('technologies')->onDelete('cascade'); // Relation avec Technologie
            $table->foreignId('transfert_competence_id')->constrained('transfert_competences')->onDelete('cascade'); // Relation avec TransfertCompetence
            $table->timestamps(); // Champs created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('technologie_transfert_competence');
    }
};
