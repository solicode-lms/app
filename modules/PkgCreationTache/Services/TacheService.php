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
 * @see docs/1.scenarios/PkgCreationProjet/Projet/creation_projet_planifie.scenario.mmd Scénario: Création Projet & Tâches
 * @see docs/1.scenarios/PkgCreationProjet/Tache/modification_tache.scenario.mmd Scénario: Modification Tâche
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



    /**
     * Vérifie si tous les apprenants assignés à un projet 
     * ont validé un chapitre donné.
     *
     * @param int $projectId
     * @param int $chapitreId
     * @return bool
     */
    public function checkAllLearnersValidatedChapter(int $projectId, int $chapitreId): bool
    {
        // Récupérer tous les apprenants assignés à ce projet via les affectations
        // On suppose que l'agent Business sait qu'on doit interroger RealisationProjet
        // pour connaitre les apprenants "actifs" du projet. 
        // Note: RealisationProject est lié à AffectationProjet qui lie Groupe -> Projet.

        // TODO : il faut récuperer les apprenants depuis affectation pas depuis Réalisation 
        $apprenantsIds = \Modules\PkgRealisationProjets\Models\RealisationProjet::whereHas('affectationProjet', function ($q) use ($projectId) {
            $q->where('projet_id', $projectId);
        })->pluck('apprenant_id')->unique();

        if ($apprenantsIds->isEmpty()) {
            // S'il n'y a aucun apprenant, on considère que "Tous" n'ont pas validé (car "Tous" = vide).
            // Mais contextuellement, si pas d'apprenant, pas besoin de bloquer la tâche ?
            // Si on retourne true, on bloque la création. Si false, on crée.
            // Si pas d'apprenant, on devrait pouvoir créer la tâche pour le futur. Donc return false.
            return false;
        }

        $totalApprenants = $apprenantsIds->count();

        // Compter combien d'apprenants (distincts) ont validé ce chapitre
        $validatedLearners = \Modules\PkgApprentissage\Models\RealisationChapitre::where('chapitre_id', $chapitreId)
            ->whereHas('etatRealisationChapitre', fn($q) => $q->where('code', 'DONE')) // Validation stricte
            ->join('realisation_uas', 'realisation_chapitres.realisation_ua_id', '=', 'realisation_uas.id')
            ->whereIn('realisation_uas.apprenant_id', $apprenantsIds)
            ->distinct('realisation_uas.apprenant_id')
            ->count('realisation_uas.apprenant_id');

        return $validatedLearners >= $totalApprenants;
    }

}
