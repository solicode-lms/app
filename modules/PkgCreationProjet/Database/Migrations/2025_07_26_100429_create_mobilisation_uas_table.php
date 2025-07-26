<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobilisation_uas', function (Blueprint $table) {
            $table->id();
            $table->longText('criteres_evaluation_prototype')->nullable();
            $table->longText('criteres_evaluation_projet')->nullable();
            $table->float('bareme_evaluation_prototype')->default(0);
            $table->float('bareme_evaluation_projet')->default(0);
            $table->longText('description')->nullable();
            
            // Relations
            $table->foreignId('projet_id')
                  ->constrained('projets')
                  ->onDelete('cascade');

            $table->foreignId('unite_apprentissage_id')
                  ->constrained('unite_apprentissages')
                  ->onDelete('cascade');

            $table->string('reference')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobilisation_uas');
    }
};
