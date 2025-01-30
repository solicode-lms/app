<?php


namespace Modules\PkgApprenants\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('apprenants', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('prenom_arab');
            $table->string('nom_arab');
            $table->string('tele_num');
            $table->string('profile_image')->nullable();
            $table->string('matricule')->unique();
            $table->char('sexe');
            $table->boolean('actif')->default(true);
            $table->string('diplome', 100)->nullable();
            $table->date('date_naissance')->nullable();
            $table->date('date_inscription')->nullable();
            $table->string('lieu_naissance', 255)->nullable();
            $table->string('cin', 50)->nullable();
            $table->longText('adresse')->nullable();
            $table->timestamps();
            $table->foreignId('niveaux_scolaire_id')->nullable()->constrained('niveaux_scolaires');
            $table->foreignId('nationalite_id')->nullable()->constrained('nationalites');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('reference')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apprenants');
    }
};
