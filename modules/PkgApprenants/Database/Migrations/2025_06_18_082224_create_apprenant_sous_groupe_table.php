<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apprenant_sous_groupe', function (Blueprint $table) {
            $table->timestamps();

            $table->foreignId('apprenant_id')
                  ->constrained('apprenants')
                  ->onDelete('cascade');

            $table->foreignId('sous_groupe_id')
                  ->constrained('sous_groupes')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apprenant_sous_groupe');
    }
};
