<?php

namespace Modules\PkgRealisationTache\Services;

use Modules\PkgRealisationTache\Services\Base\BaseRealisationTacheService;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheCrudTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheActionsTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheGetterTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheJobTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheMassCrudTrait;

/**
 * Classe RealisationTacheService pour gérer la persistance de l'entité RealisationTache.
 *
 * Architecture modulaire via Traits :
 * @uses RealisationTacheCrudTrait Gestion du cycle de vie CRUD, Hooks (before/after) et Règles Métier.
 * @uses RealisationTacheActionsTrait Actions métier spécifiques.
 * @uses RealisationTacheGetterTrait Méthodes de lecture, filtres et scopes.
 * @uses RealisationTacheJobTrait Gestion des Jobs asynchrones.
 * @uses RealisationTacheMassCrudTrait Opérations de masse.
 */
class RealisationTacheService extends BaseRealisationTacheService
{
    use RealisationTacheCrudTrait,
        RealisationTacheJobTrait,
        RealisationTacheActionsTrait,
        RealisationTacheGetterTrait,
      
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

}
