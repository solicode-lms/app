<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chapitres', function (Blueprint $table) {
            $table->id();

            $table->string('reference')->unique();
            $table->string('nom');
            $table->string('lien')->nullable(); // lien vers ressource externe ou support
            $table->text('description')->nullable();
            $table->unsignedInteger('ordre')->default(0);
            $table->boolean('isOfficiel')->default(true);

            // 🔗 Relation vers UA (obligatoire) : cascade
            $table->foreignId('unite_apprentissage_id')
                  ->constrained('unite_apprentissages')
                  ->onDelete('cascade');

            // 🔗 Relation vers formateur (facultative) : set null
            $table->foreignId('formateur_id')
                  ->nullable()
                  ->constrained('formateurs')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapitres');
    }
};
