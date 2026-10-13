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
        Schema::create('log_activite_formateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formateur_id')->constrained('formateurs')->onDelete('cascade');
            $table->string('type_activite', 50);
            $table->unsignedInteger('duree_secondes')->default(0);
            $table->date('date_activite');
            $table->string('semaine_annee', 10);
            $table->string('reference')->unique();
            $table->timestamps();

            $table->index(['formateur_id', 'semaine_annee']);
            $table->index(['formateur_id', 'date_activite']);
            $table->index(['type_activite']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_activite_formateurs');
    }
};
