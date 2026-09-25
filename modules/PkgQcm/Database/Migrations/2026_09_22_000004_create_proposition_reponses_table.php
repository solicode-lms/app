<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('proposition_reponses', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->longText('libelle');
            $table->boolean('is_correcte')->default(false);
            $table->integer('ordre')->nullable();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('proposition_reponses');
    }
};