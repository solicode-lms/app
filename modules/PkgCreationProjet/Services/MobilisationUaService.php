<?php
namespace Modules\PkgCreationProjet\Services;

use Modules\PkgCreationProjet\Services\Base\BaseMobilisationUaService;
use Modules\PkgCompetences\Models\UniteApprentissage;

/**
 * Classe MobilisationUaService pour gérer la persistance de l'entité MobilisationUa.
 * 
 * Architecture modulaire via Traits :
 * @uses Traits\MobilisationUa\MobilisationUaCrudTrait Gestion du cycle de vie CRUD et Règles Métier (Cascade Delete).
 * @uses Traits\MobilisationUa\MobilisationUaRelationsTrait Gestion des synchronisations complexes (Tâches, Réalisations).
 * @uses Traits\MobilisationUa\MobilisationUaDataCalculTrait Gestion des calculs de données et enrichissement (Critères).
 * 
 * @see docs/1.scenarios/PkgCreationProjet/MobilisationUa/mobilisation_ua_creation.scenario.mmd Scénario: Création Mobilisation UA (Workflow)
 * @see docs/1.scenarios/PkgCreationProjet/MobilisationUa/mobilisation_ua_suppression.scenario.mmd Scénario: Suppression Mobilisation UA (Workflow)
 */
class MobilisationUaService extends BaseMobilisationUaService
{
    use Traits\MobilisationUa\MobilisationUaCrudTrait;
    use Traits\MobilisationUa\MobilisationUaRelationsTrait;
    use Traits\MobilisationUa\MobilisationUaDataCalculTrait;

    // La logique est désormais déléguée aux Traits pour plus de modularité.
}
