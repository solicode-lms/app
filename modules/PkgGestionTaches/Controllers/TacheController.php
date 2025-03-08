<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers;

use Illuminate\Http\Request;
use Modules\PkgGestionTaches\Controllers\Base\BaseTacheController;
use Modules\PkgGestionTaches\Services\TacheService;

class TacheController extends BaseTacheController
{
    public function getTacheByAffectationProjetId($affectationProjetId)
    {
        $taches = (new TacheService())->getTacheByAffectationProjetId($affectationProjetId);

        // Convertir chaque objet en tableau JSON-friendly
        return response()->json($taches->map(fn($tache) => [
            'id' => $tache->id,
            'titre' => $tache->__toString(), // Assurez-vous que le champ `titre` existe
        ]));
    }

}
