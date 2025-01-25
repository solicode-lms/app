<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du widget
            $table->foreignId('type_id')->constrained('widget_types')->onDelete('cascade');
            $table->foreignId('model_id')->constrained('sys_models')->onDelete('cascade'); // Référence sys_models
            $table->foreignId('operation_id')->constrained('widget_operations')->onDelete('cascade');
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->string('label')->nullable();
            $table->json('parameters')->nullable(); // Conditions et autres paramètres
            $table->string('reference')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widgets');
    }
};
