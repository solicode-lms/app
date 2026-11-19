<?php


namespace Modules\PkgRealisationTache\Models;
use Modules\PkgRealisationTache\Models\Base\BaseTacheAffectation;

class TacheAffectation extends BaseTacheAffectation
{
    protected $casts = [
        'apprenant_live_coding_cache' => 'array',
    ];

    /**
     * Retourne le seuil effectif de réalisation pour déclencher le Live Coding.
     * Cascade de priorité :
     * 1. Seuil explicite de la Tâche (si renseigné)
     * 2. Seuil de l'Affectation de projet du groupe (si renseigné)
     * 3. Seuil spécifique porté par la TacheAffectation (si renseigné)
     * 4. Valeur par défaut : 50%
     *
     * @return int
     */
    public function getSeuilLiveCoding(): int
    {
        if ($this->tache && !is_null($this->tache->seuil_live_coding)) {
            return (int) $this->tache->seuil_live_coding;
        }

        if ($this->affectationProjet && !is_null($this->affectationProjet->seuil_live_coding)) {
            return (int) $this->affectationProjet->seuil_live_coding;
        }

        if (!is_null($this->seuil_live_coding)) {
            return (int) $this->seuil_live_coding;
        }

        return 50;
    }
}
