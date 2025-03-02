<?php



namespace Modules\PkgCompetences\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('competences', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('mini_code')->nullable();;
            $table->string('nom');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->foreignId('module_id')->constrained('modules');
            $table->string('reference')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competences');
    }
};
