<?php

use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\PkgCompetences\Services\UniteApprentissageService;

$ancienReference = 'DMB-UA.021.11';
$nouvelleReference = 'DMB-UA.201.11';

$ua = UniteApprentissage::where('reference', $ancienReference)->first();

if ($ua) {
    $uniteApprentissageService = new UniteApprentissageService();
    $uniteApprentissageService->update($ua->id, [
        'reference' => $nouvelleReference,
        'code' => 'UA.201.11',
    ]);
    echo "UniteApprentissage mise à jour : {$ancienReference} -> {$nouvelleReference}\n";
} else {
    echo "Aucune UniteApprentissage trouvée avec la référence : {$ancienReference}\n";
}
