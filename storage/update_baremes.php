<?php

 
$tachesN2N3 = \Modules\PkgCreationTache\Models\Tache::whereHas('phaseEvaluation', function ($query) {
    $query->whereIn('code', ['N2', 'N3']);
})->get();

$tacheService = new \Modules\PkgCreationTache\Services\TacheService();
foreach ($tachesN2N3 as $tache) {
    $tacheService->update($tache->id, ['updated_at' => now()]);
}
echo $tachesN2N3->count() . " Taches N2/N3 (Live coding et Realisation) recalculees.\n";
