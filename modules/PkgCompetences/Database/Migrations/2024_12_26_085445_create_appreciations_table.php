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
        Schema::create('appreciations', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->string('nom'); // Nom de l'appréciation
            $table->text('description')->nullable(); // Description
            $table->float('noteMin'); // Note minimale
            $table->float('noteMax'); // Note maximale

            
            $table->unsignedBigInteger('formateur_id'); // Relation avec Formateur
            $table->foreign('formateur_id')
                  ->references('id')
                  ->on('formateurs')
                  ->onDelete('cascade');

            $table->timestamps(); // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appreciations');
    }
};
