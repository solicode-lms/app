<?php
namespace Modules\PkgQcm\Controllers;

use Modules\PkgQcm\Controllers\Base\BaseAffectationQcmProjetController;
use Modules\PkgQcm\Models\AffectationQcmProjet;

class AffectationQcmProjetController extends BaseAffectationQcmProjetController
{
    /**
     * Affiche la vue contenant le prompt IA généré pour l'Unité d'Apprentissage.
     * 
     * @DynamicPermissionIgnore
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function prompt(string $id)
    {
        $this->authorizeAction('update');
        // 1. Récupérer l'affectation avec les relations en cascade
        $affectation = AffectationQcmProjet::with([
            'qcm', 
            'affectationProjet.projet.mobilisationUas.uniteApprentissage.chapitres'
        ])->findOrFail($id);
        
        // 2. Extraire toutes les Unités d'Apprentissage (UAs) mobilisées
        $uas = $affectation->affectationProjet->projet->mobilisationUas->pluck('uniteApprentissage')->filter();

        // 3. Renvoyer la vue Blade avec les données
        return view('PkgQcm::affectationQcmProjet.prompt', compact('affectation', 'uas'));
    }
}
