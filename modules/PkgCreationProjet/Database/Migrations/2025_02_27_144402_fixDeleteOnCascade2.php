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
       // Delete Validation on delete TransfertCompetence
        Schema::table('validations', function (Blueprint $table) {
            $table->dropForeign(['transfert_competence_id']);
            $table->foreign('transfert_competence_id')
                ->references('id')
                ->on('transfert_competences')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixDeleteOnCascade2');
    }
};
