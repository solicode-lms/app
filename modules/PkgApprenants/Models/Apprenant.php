<?php


namespace Modules\PkgApprenants\Models;
use Modules\PkgApprenants\Models\Base\BaseApprenant;
use Modules\PkgGestionTaches\Models\RealisationTache;

class Apprenant extends BaseApprenant
{

    protected static function boot()
    {
        parent::boot();

        // Colonne dynamique : nombre_realisation_taches_en_cours
        $sql = "SELECT count(*) FROM realisation_taches rt JOIN realisation_projets rp ON rt.realisation_projet_id = rp.id JOIN etat_realisation_taches ert ON rt.etat_realisation_tache_id = ert.id WHERE rp.apprenant_id = apprenants.id AND ert.nom = 'En cours'";
        static::addDynamicAttribute('nombre_realisation_taches_en_cours', $sql);
    }

    public function getFormateurId()
    {
        return optional($this->realisationProjets->first()?->affectationProjet?->projet?->formateur)->id;
    }

    public function __toString()
    {
        return ($this->nom ?? "") . " " . $this->prenom ?? "";
    }

}
