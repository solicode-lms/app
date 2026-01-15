<?php

namespace Modules\PkgRealisationProjets\Models;
use Modules\PkgRealisationProjets\Models\Base\BaseAffectationProjet;

class AffectationProjet extends BaseAffectationProjet
{

   protected $with = [
       'projet',
       'projet.formateur',
       'projet.formateur.groupes',
       'groupe',
    ];

    public function __toString()
    {
        // Vérifier si le formateur a un seul groupe
        $formateur = $this->projet->formateur ?? null;
        
        if ($formateur && $formateur->groupes->count() === 1) {
            return (string) $this->projet->titre ; // Afficher uniquement le projet
        }

        // Sinon, afficher le projet + le groupe
        return (string) $this->projet?->titre . " [" . ($this->groupe?->code ?? "Groupe inconnu") . "]";
    }

    public function generateReference(): string
    {
        $refGroup = !empty($this->sousGroupe)
            ? $this->sousGroupe->reference
            : $this->groupe->reference;

        return $this->projet->reference . '.' . $refGroup;
    }
}
