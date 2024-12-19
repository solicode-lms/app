<?php



namespace Modules\PkgAuthentification\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // La création de la table role est génrer par Spatie

        // Schema::create('roles', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('description');



        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //  Schema::dropIfExists('roles');
    }
};
