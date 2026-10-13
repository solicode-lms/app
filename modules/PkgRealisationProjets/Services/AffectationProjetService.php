<?php

namespace Modules\PkgRealisationProjets\Services;

use Modules\PkgRealisationProjets\Services\Base\BaseAffectationProjetService;
use Modules\PkgRealisationProjets\Services\Traits\AffectationProjet\AffectationProjetCrudTrait;
use Modules\PkgRealisationProjets\Services\Traits\AffectationProjet\AffectationProjetJobTrait;
use Modules\PkgRealisationProjets\Services\Traits\AffectationProjet\AffectationProjetGetterTrait;
use Modules\PkgRealisationProjets\Services\Traits\AffectationProjet\AffectationProjetCalculTrait;

/**
 * Classe AffectationProjetService pour gérer la persistance de l'entité AffectationProjet.
 * 
 * @uses AffectationProjetCrudTrait Gestion du cycle de vie (create, update, delete) et hooks CRUD.
 * @uses AffectationProjetJobTrait Gestion des jobs asynchrones et listeners.
 * @uses AffectationProjetGetterTrait Méthodes de récupération et scopes complexes.
 * @uses AffectationProjetCalculTrait Calculs et détermination de la date de lancement.
 */
class AffectationProjetService extends BaseAffectationProjetService
{
    use AffectationProjetCrudTrait, 
        AffectationProjetJobTrait, 
        AffectationProjetGetterTrait,
        AffectationProjetCalculTrait;

    protected array $index_with_relations = [
        'evaluateurs'
    ];
}
