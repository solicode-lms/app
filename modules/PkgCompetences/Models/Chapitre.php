<?php


namespace Modules\PkgCompetences\Models;
use Modules\PkgCompetences\Models\Base\BaseChapitre;

class Chapitre extends BaseChapitre
{


    public function getMicroCompetenceIdAttribute()
    {
        return $this->uniteApprentissage
            ? $this->uniteApprentissage->micro_competence_id
            : null;
    }


    public function generateReference(): string
    {
        return $this->uniteApprentissage->microCompetence->competence->module->filiere->reference . "-" . $this->code ;
    }

    public function __toString()
    {
        return ($this->code ?? "") . "-" . ($this->nom ?? "");
    }

    /**
     * Scope pour ordonner les chapitres selon etape puis ordre (avec fallback sur ordre si etape est null).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderedByEtapeEtOrdre($query)
    {
        return $query->orderByRaw('CASE WHEN etape IS NOT NULL THEN 0 ELSE 1 END ASC')
                     ->orderBy('etape', 'asc')
                     ->orderBy('ordre', 'asc')
                     ->orderBy('id', 'asc');
    }


}

