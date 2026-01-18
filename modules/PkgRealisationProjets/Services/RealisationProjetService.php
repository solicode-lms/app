<?php

namespace Modules\PkgRealisationProjets\Services;

use Modules\PkgRealisationProjets\Services\Base\BaseRealisationProjetService;
use Modules\PkgRealisationProjets\Services\Traits\RealisationProjet\RealisationProjetGetterTrait;
use Modules\PkgRealisationProjets\Services\Traits\RealisationProjet\RealisationProjetCrudTrait;
use Modules\PkgRealisationProjets\Services\Traits\RealisationProjet\RealisationProjetActionsTrait;
use Modules\PkgRealisationProjets\Services\Traits\RealisationProjet\RealisationProjetCalculTrait;
use Modules\PkgRealisationProjets\Services\Traits\RealisationProjet\RealisationProjetJobTrait;


/**
 * 
 * Classe RealisationProjetService pour gérer la persistance de l'entité RealisationProjet.
 */
class RealisationProjetService extends BaseRealisationProjetService
{
    use RealisationProjetGetterTrait,
        RealisationProjetCrudTrait,
        RealisationProjetJobTrait,
        RealisationProjetActionsTrait,
        RealisationProjetCalculTrait;


    protected array $index_with_relations = [
        'affectationProjet',
        'affectationProjet.projet',
        'affectationProjet.projet.livrables',
        'apprenant',
        'livrablesRealisations',
        'etatsRealisationProjet',
    ];
}
