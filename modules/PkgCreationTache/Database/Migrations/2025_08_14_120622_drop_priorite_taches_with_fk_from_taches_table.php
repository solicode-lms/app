<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1) Supprimer la clé étrangère et la colonne dans taches
        Schema::table('taches', function (Blueprint $table) {
            if (Schema::hasColumn('taches', 'priorite_tache_id')) {
                $table->dropForeign(['priorite_tache_id']);
                $table->dropColumn('priorite_tache_id');
            }
        });

        // 2) Supprimer la table priorite_taches
        Schema::dropIfExists('priorite_taches');
    }

    public function down()
    {
        // 1) Restaurer la table priorite_taches
        Schema::create('priorite_taches', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->integer('ordre');
            $table->longText('description')->nullable();
            $table->string('reference')->unique();
            $table->foreignId('formateur_id')
                ->constrained('formateurs')
                ->onDelete('cascade');
            $table->timestamps();
        });

        // 2) Restaurer la colonne et la FK dans taches
        Schema::table('taches', function (Blueprint $table) {
            $table->foreignId('priorite_tache_id')
                ->nullable()
                ->constrained('priorite_taches')
                ->onDelete('set null')
                ->after('projet_id');
        });
    }
};
