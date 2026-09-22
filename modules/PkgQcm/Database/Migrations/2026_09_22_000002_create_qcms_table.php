<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qcms', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('titre');
            $table->longText('description')->nullable();
            $table->integer('duree_minutes')->nullable();
            $table->boolean('is_duree_limitee')->default(false);
            $table->boolean('is_publie')->default(false);

            // Relation ManyToOne → formateurs (PkgFormation)
            $table->foreignId('formateur_id')
                  ->nullable()
                  ->constrained('formateurs')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qcms');
    }
};
