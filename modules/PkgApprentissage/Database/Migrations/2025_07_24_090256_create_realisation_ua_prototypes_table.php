<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisation_ua_prototypes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();

            // Colonnes principales
            $table->float('note')->default(0);
            $table->float('bareme')->default(0);
            $table->text('remarque_formateur')->nullable();
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();

            // Relation vers realisation_uas
            $table->foreignId('realisation_ua_id')
                  ->constrained('realisation_uas')
                  ->onDelete('cascade');

            // Relation vers realisation_taches (ON DELETE CASCADE)
            $table->foreignId('realisation_tache_id')
                  ->constrained('realisation_taches')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisation_ua_prototypes');
    }
};
