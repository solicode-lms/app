<?php


namespace Modules\PkgFormation\Services;

use Carbon\Carbon;
use Modules\PkgFormation\Models\AnneeFormation;
use Modules\PkgFormation\Services\Base\BaseAnneeFormationService;

/**
 * Classe AnneeFormationService pour gérer la persistance de l'entité AnneeFormation.
 */
class AnneeFormationService extends BaseAnneeFormationService
{
    
    /**
     * Récupère ou crée une année de formation à partir de la date d'inscription.
     *
     * @param Carbon $date_inscription
     * @return AnneeFormation
     */
    public function getOrCreateFromDateInscription(Carbon $date_inscription): AnneeFormation
    {
        $annee_debut = $date_inscription->month >= 9 ? $date_inscription->year : $date_inscription->year - 1;
        $annee_fin = $annee_debut + 1;
        $reference = "{$annee_debut}-{$annee_fin}";
        $titre = $reference;

        return AnneeFormation::firstOrCreate(
            ['reference' => $titre],
            [
                'date_debut' => "{$annee_debut}-09-01",
                'date_fin' => "{$annee_fin}-08-31",
                'titre' =>  $titre
            ]
        );
    }


        /**
     * Récupère l'année de formation en cours depuis la base de données, sinon la crée.
     *
     * @return AnneeFormation
     */
    public function getCurrentAnneeFormation(): AnneeFormation
    {
        $currentDate = Carbon::now();
        $annee_debut = $currentDate->month >= 9 ? $currentDate->year : $currentDate->year - 1;
        $annee_fin = $annee_debut + 1;
        $reference = "{$annee_debut}-{$annee_fin}";

        return AnneeFormation::firstOrCreate(
            ['reference' => $reference],
            [
                'date_debut' => "{$annee_debut}-09-01",
                'date_fin' => "{$annee_fin}-08-31",
                'titre' => $reference
            ]
        );
    }
}
