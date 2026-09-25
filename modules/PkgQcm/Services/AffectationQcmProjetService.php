<?php
namespace Modules\PkgQcm\Services;

use Modules\PkgQcm\Services\Base\BaseAffectationQcmProjetService;
use Modules\PkgQcm\Models\RealisationQcm;
use Modules\PkgQcm\Models\EtatRealisationQcm;
use Illuminate\Support\Str;

/**
 * Classe AffectationQcmProjetService pour gérer la persistance de l'entité AffectationQcmProjet.
 */
class AffectationQcmProjetService extends BaseAffectationQcmProjetService
{
    public function afterCreateRules($item)
    {
        $affectationProjet = $item->affectationProjet;
        if (!$affectationProjet) {
            return;
        }

        $apprenants = collect();
        if ($affectationProjet->sous_groupe_id && $affectationProjet->sousGroupe) {
            $apprenants = $affectationProjet->sousGroupe->apprenants;
        } elseif ($affectationProjet->groupe_id && $affectationProjet->groupe) {
            $apprenants = $affectationProjet->groupe->apprenants;
        }

        if ($apprenants->isEmpty()) {
            return;
        }
        $etatAFaire = EtatRealisationQcm::where('reference', 'A_FAIRE')->first();
        $etatId = $etatAFaire ? $etatAFaire->id : null;

        foreach ($apprenants as $apprenant) {
            RealisationQcm::create([
                'affectation_qcm_projet_id' => $item->id,
                'qcm_id' => $item->qcm_id,
                'apprenant_id' => $apprenant->id,
                'etat_realisation_qcm_id' => $etatId,
                'reference' => Str::uuid(),
            ]);
        }
    }
}
