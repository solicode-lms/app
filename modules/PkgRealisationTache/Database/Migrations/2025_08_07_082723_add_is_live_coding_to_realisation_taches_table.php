<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('realisation_taches', function (Blueprint $table) {
            // ✅ Champ booléen pour indiquer si la réalisation est en live coding
            $table->boolean('is_live_coding')
                ->default(false)
                ->after('dateFin');

              // Relation obligatoire avec cascade
                $table->foreignId('tache_affectation_id')
                    ->constrained('tache_affectations')
                    ->onDelete('cascade')
                    ->after('is_live_coding');
                    });
    }

    public function down()
    {
        Schema::table('realisation_taches', function (Blueprint $table) {
            $table->dropForeign(['tache_affectation_id']);
            $table->dropColumn(['is_live_coding', 'tache_affectation_id']);
        });
    }
};
