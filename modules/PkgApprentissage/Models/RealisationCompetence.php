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

    public function generateReference(): string
    {
        return  $this->competence->reference . "-" . $this->apprenant->reference  ; 
    }

    public function getLecturePedagogiqueAttribute(): ?string
    {
        $tauxRythme = $this->taux_rythme_cache ?? null;
        $note = $this->note_cache ?? null;
        $bareme = $this->bareme_cache ?? null;

        if (is_null($tauxRythme) || is_null($note) || is_null($bareme) || $bareme == 0) {
            return null; // Pas de lecture possible
        }

        // ✅ Déterminer la qualité : "Bonne" ou "Faible"
        $qualite = ($note / $bareme) >= 0.6 ? 'Bonne' : 'Faible';

        // ✅ Tableau de configuration (lecture pédagogique)
        $grille = [
            'bas' => [
                'Faible' => "⚠️ En difficulté – retard cumulé et acquis fragiles",
                'Bonne'  => "🐢 Profil qualitatif lent – doit accélérer le rythme",
            ],
            'normal' => [
                'Faible' => "🔧 À consolider – suit le rythme mais acquis fragiles",
                'Bonne'  => "✅ Dans le rythme – progression et acquis solides",
            ],
            'avance' => [
                'Faible' => "⚡ Travail superficiel – avance vite mais fragilité",
                'Bonne'  => "🌟 Excellence – avance vite et bien, profil autonome",
            ],
        ];

        // ✅ Déterminer la clé du taux de rythme
        if ($tauxRythme < 70) {
            $niveau = 'bas';
        } elseif ($tauxRythme <= 100) {
            $niveau = 'normal';
        } else {
            $niveau = 'avance';
        }

        return $grille[$niveau][$qualite] ?? null;
    }

}
