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
        Schema::create('groupes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('nom')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('filiere_id')->nullable()->constrained('filieres');
            $table->foreignId('annee_formation_id')->nullable()->constrained('annee_formations');
            $table->string('reference')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groupes');
    }
};
