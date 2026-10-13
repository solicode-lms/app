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
        Schema::create('bordereaux_officiels', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->foreignId('groupe_id')->constrained('groupes')->cascadeOnDelete();
            $table->foreignId('annee_formation_id')->nullable()->constrained('annee_formations')->nullOnDelete();
            $table->unsignedInteger('nb_apprenants')->default(0);
            $table->decimal('taux_conformite_calcul', 5, 2)->default(100.00);
            $table->string('statut', 40)->default('VALIDE_SANS_ANOMALIE');
            $table->timestamp('date_scellement')->nullable();
            $table->string('hash_signature')->nullable();
            $table->foreignId('genere_par_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bordereaux_officiels');
    }
};
