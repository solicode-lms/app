<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationCompetence;

class RealisationCompetence extends BaseRealisationCompetence
{
    public function __toString()
    {
        return $this->competence ?? "";
    }

    /**
     * Attribut calculé "note / bareme".
     */
    public function getNoteSurBaremeAttribute()
    {
        $note = $this->note_cache ?? null;
        $bareme = $this->bareme_cache ?? null;

        if (!is_null($note) && !is_null($bareme) && $bareme != 0) {
            $ratio = round($note / $bareme, 2) * 100; // ratio sur 2 décimales
            return "{$note}/{$bareme} ({$ratio}%)";
        }

        return "—"; // Rien si l'un des deux manque ou bareme = 0
    }
}
