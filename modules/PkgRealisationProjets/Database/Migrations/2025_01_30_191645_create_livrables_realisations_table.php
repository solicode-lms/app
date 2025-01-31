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
        Schema::create('livrables_realisations', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée (bigint)
            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('lien')->nullable(); // Lien vers le livrable (ex: Google Drive, GitHub)
            $table->foreignId('livrable_id')->constrained('livrables');
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
        Schema::dropIfExists('livrables_realisation');
    }
};
