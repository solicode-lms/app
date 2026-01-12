<?php

namespace Modules\PkgRealisationTache\Services;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgCreationTache\Services\TacheService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgRealisationTache\Services\Base\BaseRealisationTacheService;
use Modules\PkgRealisationTache\Services\RealisationTacheService\RealisationTacheServiceCrud;
use Modules\PkgRealisationTache\Services\RealisationTacheService\RealisationTacheCalculeProgression;

use Modules\PkgRealisationTache\Services\RealisationTacheService\RealisationTacheServiceWidgets;
use Modules\PkgRealisationTache\Services\RealisationTacheService\RealisationTacheWorkflow;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationTacheService;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgApprentissage\Services\RealisationUaProjetService;
use Modules\PkgApprentissage\Services\RealisationUaPrototypeService;
use Modules\PkgApprentissage\Models\RealisationChapitre;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Modules\PkgCreationProjet\Models\MobilisationUa;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheCrudTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheActionsTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheGetterTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheJobTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheMassCrudTrait;

/**
 * Classe RealisationTacheService pour gérer la persistance de l'entité RealisationTache.
 */
class RealisationTacheService extends BaseRealisationTacheService
{
    use
        RealisationTacheCrudTrait,
        RealisationTacheActionsTrait,
        RealisationTacheGetterTrait,
        RealisationTacheJobTrait,
        RealisationTacheMassCrudTrait;


    protected array $index_with_relations = [
        'tache',
        'realisationChapitres',
        'tacheAffectation',
        'tache.livrables',
        'etatRealisationTache',
        'historiqueRealisationTaches',
        'realisationProjet.apprenant',
        'realisationProjet.affectationProjet',
        'tache.livrables.natureLivrable',
        'livrablesRealisations.livrable.taches',
        'realisationProjet.realisationTaches.tache',
    ];














    /**
     * Construit la requête pour récupérer les réalisations de tâches
     * en état "REVISION_NECESSAIRE" et priorité inférieure.
     *
     * @param  int  $realisationTacheId
     * @return Builder
     */

}
