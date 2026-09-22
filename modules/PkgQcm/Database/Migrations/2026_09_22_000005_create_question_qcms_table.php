<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_qcms', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->integer('ordre')->nullable();
            $table->float('bareme')->nullable();

            // Relation ManyToOne → qcms
            $table->foreignId('qcm_id')
                  ->constrained('qcms')
                  ->onDelete('cascade');

            // Relation ManyToOne → question_libs
            $table->foreignId('question_lib_id')
                  ->constrained('question_libs')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_qcms');
    }
};
