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
        Schema::create('live_codings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // Règle d'or : identifiant unique
            $table->foreignId('realisation_tache_id')->constrained('realisation_taches')->onDelete('cascade');
            $table->integer('tentative')->default(1);
            $table->boolean('est_valide')->default(false);
            $table->dateTime('date_passage');
            $table->longText('commentaire')->nullable();
            $table->timestamps();

            $table->index(['realisation_tache_id', 'tentative']);
            $table->index('est_valide');
            $table->index('date_passage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_codings');
    }
};
