<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationUaProjet;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;

class RealisationUaProjet extends BaseRealisationUaProjet
{


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // Colonne dynamique : realisation_projet_id
        $sql = "SELECT rt.realisation_projet_id
                FROM realisation_taches rt
                WHERE rt.id = realisation_ua_projets.realisation_tache_id";
        static::addDynamicAttribute('realisation_projet_id', $sql);
    }

    protected $with = [
        'realisationTache.etatRealisationTache.sysColor',
        'realisationUa.realisationChapitres.chapitre',
        'realisationUa.realisationChapitres.realisationTache.etatRealisationTache.sysColor',
        // 'prototypeRelation.realisationTache', // Retiré car hasOne simple ne garantit pas le bon prototype par apprenant
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            // Récupérer la tâche liée
            $tache = \Modules\PkgRealisationTache\Models\RealisationTache::find($model->realisation_tache_id);

            // Récupérer l’état de la tâche et son workflow
            $etat = $tache?->etatRealisationTache;
            $workflow = $etat?->workflowTache;

            if (!$workflow)
                return;

            // Récupération de l’ordre de référence de IN_PROGRESS
            static $ordreInProgress = null;
            if (is_null($ordreInProgress)) {
                $ordreInProgress = \Modules\PkgRealisationTache\Models\WorkflowTache::where('code', 'IN_PROGRESS')->value('ordre');
            }

            // Affecter date_debut si ordre ≥ IN_PROGRESS
            if (is_null($model->date_debut) && $workflow->ordre >= $ordreInProgress) {
                $model->date_debut = now();
            }

            // Affecter date_fin si code = DONE
            if (is_null($model->date_fin) && $workflow->code === 'APPROVED') {
                $model->date_fin = now();
            }

            // Mettre à jour dernier_update si colonne existante
            if (in_array('dernier_update', $model->getFillable())) {
                $model->dernier_update = now();
            }
        });
    }

    public function prototypeRelation()
    {
        // Relation brute : retourne UN prototype lié à l'UA.
        // Attention : Peut retourner le prototype d'un autre apprenant si l'UA est partagée.
        // Utilisez l'accesseur 'prototype' pour obtenir le bon.
        return $this->hasOne(RealisationUaPrototype::class, 'realisation_ua_id', 'realisation_ua_id');
    }

    /**
     * Accesseur optimisé pour récupérer le prototype
     * Utilise la collection déjà chargée si possible, sinon fait une requête ciblée.
     */
    public function getPrototypeAttribute()
    {
        // Si la relation est chargée, on filtre la collection en mémoire (plus sûr)
        if ($this->relationLoaded('realisationUa')) {
            $targetProjetId = $this->realisation_projet_id;
            // On remonte à l'UA pour redescendre vers les prototypes
            // Car prototypeRelation(hasOne) risque de n'en ramener qu'un seul (le mauvais) si pas filtré
            return $this->realisationUa->realisationUaPrototypes
                ->filter(function ($proto) use ($targetProjetId) {
                    return $proto->realisation_projet_id == $targetProjetId;
                })->first();
        }

        // Sinon fallback sur l'ancienne méthode mais optimisée
        return \Modules\PkgApprentissage\Models\RealisationUaPrototype::where('realisation_ua_id', $this->realisation_ua_id)
            ->whereHas('realisationTache', function ($q) {
                $q->where('realisation_projet_id', $this->realisation_projet_id);
            })->first();
    }


}
