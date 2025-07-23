<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('critere_evaluations', function (Blueprint $table) {
            $table->id(); // id: bigint (primary key, auto-increment)
            
            // Colonne obligatoire 'reference'
            $table->string('reference')->unique();

            // Champs métiers
            $table->text('intitule');         // Nom du critère
            $table->float('bareme')->default(1); // Barème associé
            $table->integer('ordre')->default(0); // Ordre d’affichage

            // Relation avec PhaseEvaluation
            $table->foreignId('phase_evaluation_id')
                  ->constrained('phase_evaluations')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('critere_evaluations');
    }
};
