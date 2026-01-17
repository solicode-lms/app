<?php

namespace Modules\PkgCreationTache\Services;

use Modules\PkgCreationTache\Services\Base\BaseTacheService;
use Modules\PkgCreationTache\Services\Traits\Tache\TacheCrudTrait;
use Modules\PkgCreationTache\Services\Traits\Tache\TacheGetterTrait;
use Modules\PkgCreationTache\Services\Traits\Tache\TacheRelationsTrait;
use Modules\PkgCreationTache\Services\Traits\Tache\TacheActionsTrait;

/**
 * Classe TacheService pour gérer la persistance de l'entité Tache.
 */
class TacheService extends BaseTacheService
{
    use TacheCrudTrait;
    use TacheGetterTrait;
    use TacheRelationsTrait;
    use TacheActionsTrait;

    protected array $index_with_relations = [
        'projet',
        'livrables'
    ];

    protected $ordreGroupColumn = "projet_id";

}
