<?php


namespace Modules\PkgApprentissage\Models;
use Modules\PkgApprentissage\Models\Base\BaseRealisationUaProjet;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;

class RealisationUaProjet extends BaseRealisationUaProjet
{


     public function __construct(array $attributes = []) {
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
    'prototypeRelation.realisationTache',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            // Récupérer la tâche liée
            $tache = \Modules\PkgRealisationTache\Models\RealisationTache::find($model->realisation_tache_id);

            // Récupérer l’état de la tâche et son workflow
            $etat = $tache?->etatRealisationTache;
            $workflow = $etat?->workflowTache;

            if (!$workflow) return;

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
    return $this->hasOne(RealisationUaPrototype::class, 'realisation_ua_id', 'realisation_ua_id');
}
public function getPrototypeAttribute()
{
    return $this->prototypeRelation
        ->get()
        ->firstWhere('realisation_projet_id', $this->realisation_projet_id);
}


}
