<?php


namespace Modules\PkgCompetences\Services;
use Modules\PkgCompetences\Services\Base\BaseCompetenceService;

/**
 * Classe CompetenceService pour gérer la persistance de l'entité Competence.
 */
class CompetenceService extends BaseCompetenceService
{

    protected array $index_with_relations = ['module.filiere', 'module.filiere.groupes.formateurs', 'module.filiere.groupes.apprenants'];

  
}
