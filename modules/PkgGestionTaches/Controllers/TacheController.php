<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers;

use Illuminate\Support\Facades\Request;
use Modules\PkgGestionTaches\Controllers\Base\BaseTacheController;
use Modules\PkgGestionTaches\Services\TacheService;

class TacheController extends BaseTacheController
{
    public function getTachesByAffectationProjet(Request $request)
    {
        $affectation_projet_id = $request->query('affectation_projet_id');

        $taches = (new TacheService())->getTacheByAffectationProjetId($affectation_projet_id);

        return response()->json($taches);
    }

}
