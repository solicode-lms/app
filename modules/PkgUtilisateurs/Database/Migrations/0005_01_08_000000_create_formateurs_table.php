<?php



namespace Modules\PkgUtilisateurs\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('formateurs', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('prenom_arab')->nullable();
            $table->string('nom_arab')->nullable();
            $table->string('tele_num')->nullable();
            $table->string('adresse')->nullable();
            $table->string('diplome')->nullable();
            $table->integer('echelle')->nullable();
            $table->integer('echelon')->nullable();
            $table->string('profile_image')->nullable();

            $table->timestamps();
           

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formateurs');
    }
};
