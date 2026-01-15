<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgCreationProjet\Services\ProjetService;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Exécuter le seeder pour s'assurer que les phases existent
        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'Modules\\PkgCreationTache\\Database\\Seeders\\PhaseProjetSeeder',
            '--force' => true
        ]);

        // Instanciation directe du service
        $projetService = new ProjetService();

        // Récupération de tous les projets
        $projets = Projet::all();

        foreach ($projets as $projet) {
            // Appel de la méthode de correction pour chaque projet
            $projetService->fixPhasesForExistingTasks($projet->id);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Pas de retour en arrière possible/nécessaire pour cette migration de données
    }
};
