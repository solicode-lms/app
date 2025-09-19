<?php


namespace Modules\PkgRealisationProjets\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\PkgRealisationProjets\Models\Base\BaseRealisationProjet;

class RealisationProjet extends BaseRealisationProjet
{

    protected $with = [
       'affectationProjet',
       'apprenant',
       'etatsRealisationProjet'
    ];

    public function __toString()
    {
        return ($this->affectationProjet ?? "") . "-" . ($this->apprenant ?? "") ;
    }



    protected static function booted(): void
    {
        static::addGlobalScope('apprenant_actif', function (Builder $builder) {
            $builder->whereHas('apprenant', function ($q) {
                $q->where('actif', true); 
            });
        });
    }

    /**
     * Calcule la note recalibrée selon l’échelle cible définie sur l’affectation.
     *
     * @return float  Note recalculée (2 décimales) ou note brute si pas d’échelle définie
     */
    public function calculerNoteAvecEchelle(): float
    {
        // 1. Note brute (somme des notes de realisation_taches)
        $noteBrute   = (float) ($this->note ?? 0);

        // 2. Barème total (somme des baremes de chaque tache)
        $baremeTotal = (float) ($this->bareme_note ?? 0);

        // Si aucun barème total ou nul, on renvoie la note brute arrondie
        if ($baremeTotal <= 0) {
            return round($noteBrute, 2);
        }

        // 3. Échelle cible (ex : 50) définie sur l’affectation
        $echelle = $this->affectationProjet->echelle_note_cible ?? null;

        // Si pas d’échelle cible ou invalide, on renvoie la note brute arrondie
        if (! $echelle || $echelle <= 0) {
            return round($noteBrute, 2);
        }

        // 4. Application de la règle de proportionnalité
        //    note_redim = noteBrute * echelle / baremeTotal
        $noteRedim = ($noteBrute * $echelle) / $baremeTotal;

        // 5. Arrondi final à 2 décimales
        return round($noteRedim, 2);
    }

    public function generateReference(): string
    {
        return $this->affectationProjet->reference . "-" . $this->apprenant->reference ;
    }

}
