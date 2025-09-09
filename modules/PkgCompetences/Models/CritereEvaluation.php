<?php


namespace Modules\PkgCompetences\Models;
use Modules\PkgCompetences\Models\Base\BaseCritereEvaluation;
use Illuminate\Support\Str;

class CritereEvaluation extends BaseCritereEvaluation
{

    // Slug 
    // public function generateReference(): string
    // {
    //     // Générer un slug depuis intitule
    //     $slug = Str::slug($this->intitule, '-');

    //     // Combiner avec la référence de l'unité d'apprentissage
    //     $reference = $this->uniteApprentissage->reference . '-' . $slug;

    //     // Limiter à 200 caractères maximum
    //     return Str::limit($reference, 200, '');
    // }

}
