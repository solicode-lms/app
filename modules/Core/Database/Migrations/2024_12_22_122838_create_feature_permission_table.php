<?php
// Le fichier de migration est déja exsite mais dans le PkgAutorisation
// TODO : Ajouter dans gapp une vérification l'existance de fichier dans le iPackage adversaire



namespace Modules\Core\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('feature_permission', function (Blueprint $table) {

        //     $table->timestamps();
           
        //     $table->foreignId('feature_id')->constrained('features');
        //     $table->foreignId('permission_id')->constrained('permissions');

        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('feature_permission');
    }
};
