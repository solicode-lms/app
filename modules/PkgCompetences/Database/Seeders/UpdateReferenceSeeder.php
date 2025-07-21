<?php

namespace Modules\PkgCompetences\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\PkgCompetences\Models\MicroCompetence;
use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\PkgCompetences\Models\Chapitre;
use Illuminate\Support\Facades\DB;

class UpdateReferenceSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->updateMicroCompetences();
            $this->updateUnitesApprentissage();
            $this->updateChapitres();
        });
    }

    /**
     * Met à jour les références de toutes les MicroCompétences
     */
    protected function updateMicroCompetences(): void
    {
        MicroCompetence::chunk(100, function ($microCompetences) {
            foreach ($microCompetences as $mc) {
                $mc->reference = $mc->generateReference();
                $mc->saveQuietly(); // éviter d'appeler à nouveau les events
            }
        });
    }

    /**
     * Met à jour les références de toutes les Unités d'Apprentissage
     */
    protected function updateUnitesApprentissage(): void
    {
        UniteApprentissage::chunk(100, function ($uas) {
            foreach ($uas as $ua) {
                $ua->reference = $ua->generateReference();
                $ua->saveQuietly();
            }
        });
    }

    /**
     * Met à jour les références de tous les Chapitres
     */
    protected function updateChapitres(): void
    {
        Chapitre::chunk(100, function ($chapitres) {
            foreach ($chapitres as $chapitre) {
                $chapitre->reference = $chapitre->generateReference();
                $chapitre->saveQuietly();
            }
        });
    }
}
