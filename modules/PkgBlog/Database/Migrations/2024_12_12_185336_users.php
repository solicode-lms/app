<?php



namespace Modules\PkgBlog\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // TODO : essayer de mettre le code migration de l'utilisation 
        // dans ce fichier 
        
        // L'utilisation est ajouter par Core
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email');
        //     $table->string('password');

        //     $table->timestamps();


        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('users');
    }
};
