<?php



namespace Modules\PkgFormation\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('formateur_groupe', function (Blueprint $table) {

        //     $table->timestamps();
           
        //     $table->foreignId('')->constrained('formateurs');
        //     $table->foreignId('')->constrained('groupes');

        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('formateur_groupe');
    }
};
