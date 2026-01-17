<?php

namespace Modules\PkgCreationTache\Services;

use Modules\PkgCreationTache\Services\Base\BaseTacheService;
use Modules\PkgCreationTache\Services\Traits\Tache\TacheCrudTrait;
use Modules\PkgCreationTache\Services\Traits\Tache\TacheGetterTrait;
use Modules\PkgCreationTache\Services\Traits\Tache\TacheRelationsTrait;
use Modules\PkgCreationTache\Services\Traits\Tache\TacheActionsTrait;

/**
 * Classe TacheService pour gérer la persistance de l'entité Tache.
 * 
 * Architecture modulaire via Traits :
 * @uses TacheCrudTrait Gestion du cycle de vie CRUD, Hooks (before/after) et Règles Métier de base.
 * @uses TacheGetterTrait Méthodes de lecture et filtres de requêtes spécifiques.
 * @uses TacheRelationsTrait Gestion complexe des relations (Synchronisation Apprenants, Compétences/UA).
 * @uses TacheActionsTrait Actions métier spécifiques (ex: Génération de tâches depuis une UA).
 * 
 * @see docs/1.conception/PkgCreationProjet/creation_projet/creation_projet_planifie_logic.md Workflow: Création Projet & Tâches (Algorithme)
 * @see docs/1.conception/PkgCreationTache/structure_service_tache.md Documentation Architecture Service
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
