<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('widget_utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->integer('ordre');
            $table->string('titre');
            $table->string('sous_titre');
            $table->json('config');
            $table->boolean('visible');

            $table->timestamps();
           
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('widget_id')->constrained('widgets');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widget_utilisateurs');
    }
};
