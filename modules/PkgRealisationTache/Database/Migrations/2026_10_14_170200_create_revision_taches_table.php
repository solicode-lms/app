<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('revision_taches', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // Règle d'or SoliLMS : référence unique
            $table->foreignId('realisation_tache_id')->constrained('realisation_taches')->onDelete('cascade');
            $table->integer('tentative')->default(1);
            $table->dateTime('date_demande');
            $table->longText('message_formateur')->nullable();
            $table->dateTime('date_soumission')->nullable();
            $table->dateTime('date_validation')->nullable();
            $table->boolean('est_valide')->default(false);
            $table->timestamps();

            $table->index(['realisation_tache_id', 'tentative']);
            $table->index('date_demande');
            $table->index('est_valide');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revision_taches');
    }
};
