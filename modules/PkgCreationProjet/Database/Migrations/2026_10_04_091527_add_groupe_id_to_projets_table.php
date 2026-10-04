<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projets', function (Blueprint $table) {
            $table->unsignedBigInteger('groupe_id')->nullable()->after('titre');
            $table->foreign('groupe_id')->references('id')->on('groupes');
        });

        // Reprise de données : Assigner les projets existants à leurs groupes
        $projets = DB::table('projets')->get();
        foreach ($projets as $projet) {
            $affectation = DB::table('affectation_projets')
                             ->where('projet_id', $projet->id)
                             ->first();
            
            if ($affectation && $affectation->groupe_id) {
                DB::table('projets')->where('id', $projet->id)->update([
                    'groupe_id' => $affectation->groupe_id
                ]);
            } else {
                // S'il n'y a pas d'affectation, on prend le premier groupe affecté au formateur pour la filière du projet
                $groupe = DB::table('groupes')
                            ->join('formateur_groupe', 'groupes.id', '=', 'formateur_groupe.groupe_id')
                            ->where('formateur_groupe.formateur_id', $projet->formateur_id)
                            ->where('groupes.filiere_id', $projet->filiere_id)
                            ->select('groupes.id')
                            ->first();

                if ($groupe) {
                    DB::table('projets')->where('id', $projet->id)->update([
                        'groupe_id' => $groupe->id
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projets', function (Blueprint $table) {
            $table->dropForeign(['groupe_id']);
            $table->dropColumn('groupe_id');
        });
    }
};
