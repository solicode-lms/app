<?php

namespace Modules\PkgCreationProjet\Services;

use Illuminate\Support\Facades\Auth;
use Modules\PkgCreationProjet\Services\Base\BaseProjetService;
use Modules\PkgSessions\Models\SessionFormation;
use Modules\Core\App\Exceptions\BlException;
use Modules\PkgCreationProjet\Services\Traits\Projet\ProjetActionsTrait;
use Modules\PkgCreationProjet\Services\Traits\Projet\ProjetCalculTrait;
use Modules\PkgCreationProjet\Services\Traits\Projet\ProjetRelationsTrait;
use Modules\PkgCreationProjet\Services\Traits\Projet\ProjetCrudTrait;

/**
 * Classe ProjetService pour gérer la persistance de l'entité Projet.
 * 
 * Architecture modulaire via Traits :
 * @uses Traits\Projet\ProjetCrudTrait Gestion du cycle de vie CRUD et Hooks (beforeCreateRules, afterCreateRules).
 * @uses Traits\Projet\ProjetActionsTrait Actions métier spécifiques (import, export, génération de contenu).
 * @uses Traits\Projet\ProjetCalculTrait Calculs et enrichissement de données (statistiques, agrégations).
 * @uses Traits\Projet\ProjetRelationsTrait Gestion des relations complexes et synchronisations avec entités liées.
 * 
 * @see docs/1.scenarios/PkgCreationProjet/Projet/creation_projet_libre.scenario.mmd Scénario: Création Projet Libre
 */
class ProjetService extends BaseProjetService
{

    protected $paginationLimit = 40;

    use ProjetCrudTrait,
        ProjetActionsTrait,
        ProjetCalculTrait,
        ProjetRelationsTrait;

    protected array $index_with_relations = [
        'filiere',
        'formateur',
        'livrables',
        'resources',
        'taches',
        'affectationProjets',
        'affectationProjets.groupe'
    ];



    /**
     * Initialisation des champs filtrables personnalisés.
     * Surcharge de la méthode définie dans BaseProjetService.
     */
    public function initFieldsFilterable()
    {
        $scopeVariables = $this->viewState->getScopeVariables('projet');
        $this->fieldsFilterable = [];

        // 1. Filtre Filiere (dynamique vers SessionFormation)
        if (!array_key_exists('filiere_id', $scopeVariables)) {
            $filiereService = new \Modules\PkgFormation\Services\FiliereService();
            $filiereIds = $this->getAvailableFilterValues('filiere_id');
            $filieres = $filiereService->getByIds($filiereIds);

            if ($filieres->count() > 1 || \Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgFormation::filiere.plural"),
                    'filiere_id',
                    \Modules\PkgFormation\Models\Filiere::class,
                    'code',
                    'id',
                    $filieres,
                    "[name='session_formation_id'],[name='affectationProjets.groupe_id']",       // Sélecteurs cibles à rafraîchir
                    route('sessionFormations.getData') . "," . route('groupes.getData'),         // Routes API
                    "filiere_id,filiere_id"                                                      // Clés des paramètres de filtre
                );
            }
        }


        // 4. Filtre Groupe (via AffectationProjet)
        if (!array_key_exists('affectationProjets.groupe_id', $scopeVariables)) {
            $groupeService = new \Modules\PkgApprenants\Services\GroupeService();
            $groupeIds = $this->getAvailableFilterValues('affectationProjets.groupe_id');
            $groupes = $groupeService->getByIds($groupeIds);

            $user = \Illuminate\Support\Facades\Auth::user();
            $showGroupeFilter = $user->hasRole('admin');

            if (!$showGroupeFilter) {
                // Vérifier si le formateur enseigne à plusieurs groupes
                $formateur = \Modules\PkgFormation\Models\Formateur::where('user_id', $user->id)->first();
                if ($formateur && $formateur->groupes()->count() > 1) {
                    $showGroupeFilter = true;
                }
            }

            if ($showGroupeFilter) {
                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgApprenants::groupe.singular"),
                    'affectationProjets.groupe_id',
                    \Modules\PkgApprenants\Models\Groupe::class, 
                    'code',
                    'id',
                    $groupes
                );
            }
        }



        // 2. Filtre Session Formation
        if (!array_key_exists('session_formation_id', $scopeVariables)) {
            $sessionFormationService = new \Modules\PkgSessions\Services\SessionFormationService();
            $sessionFormationIds = $this->getAvailableFilterValues('session_formation_id');
            $sessionFormations = $sessionFormationService->getByIds($sessionFormationIds);

            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgSessions::sessionFormation.plural"), 
                'session_formation_id', 
                \Modules\PkgSessions\Models\SessionFormation::class, 
                'code',
                'id',
                $sessionFormations
            );
        }
        
        // 3. Filtre Formateur
        if (!array_key_exists('formateur_id', $scopeVariables)) {
            $formateurService = new \Modules\PkgFormation\Services\FormateurService();
            $formateurIds = $this->getAvailableFilterValues('formateur_id');
            $formateurs = $formateurService->getByIds($formateurIds);

            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgFormation::formateur.plural"), 
                'formateur_id', 
                \Modules\PkgFormation\Models\Formateur::class, 
                'nom',
                'id',
                $formateurs
            );
        }

      
    }

    /**
     * Retourne la configuration des tâches à générer pour un projet donné.
     * Cette configuration définit l'ordre et les propriétés des tâches en fonction
     * des phases de projet définies en base de données.
     *
     * @param mixed $session La session de formation (pour les titres/descriptions dynamiques).
     * @return array
     */
    public static function getTasksConfig($session)
    {
        $tasksConfig = [];

        // Récupérer les phases d'évaluation nécessaires
        $phasesEval = \Modules\PkgCompetences\Models\PhaseEvaluation::pluck('id', 'code')->toArray();

        // Utilisation du modèle dans PkgCreationTache comme défini par l'utilisateur
        $phasesProjet = \Modules\PkgCreationTache\Models\PhaseProjet::orderBy('ordre')->get();

        foreach ($phasesProjet as $phase) {
            switch ($phase->reference) {
                case 'ANALYSE':
                    $tasksConfig[] = [
                        'titre' => 'Analyse',
                        'description' => 'Analyse du projet',
                        'phase_evaluation_id' => null,
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'APPRENTISSAGE':
                    $tasksConfig[] = [
                        'type' => 'Tutoriels',
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'PROTOTYPE':
                    $tasksConfig[] = [
                        'titre' => optional($session)->titre_prototype ? "Prototype : " . optional($session)->titre_prototype : 'Prototype',
                        'description' => trim((optional($session)->description_prototype ?? '') . "</br><b>Contraintes</b>" . (optional($session)->contraintes_prototype ?? '')),
                        'phase_evaluation_id' => null, // Pas d'évaluation sur le prototype statique seul
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'LIVE_CODING':
                    $tasksConfig[] = [
                        'titre' => 'Live Coding (Prototype)',
                        'description' => 'Validation des compétences via Live Coding sur le prototype.',
                        'phase_evaluation_id' => $phasesEval['N2'] ?? null, // C'est ici qu'on évaluation N2 (Adapter)
                        // Note calculée automatiquement dans TacheService
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'CONCEPTION':
                    $tasksConfig[] = [
                        'titre' => 'Conception',
                        'description' => 'Conception du projet',
                        'phase_evaluation_id' => null,
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'REALISATION':
                    $tasksConfig[] = [
                        'titre' => 'Réalisation',
                        'description' => trim((optional($session)->description_projet ?? '') . "</br><b>Contraintes</b>" . (optional($session)->contraintes_projet ?? '')),
                        'phase_evaluation_id' => $phasesEval['N3'] ?? null,
                        // Note calculée automatiquement dans TacheService
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'BESOINS':
                    // Tâche optionnelle ou future
                    break;

                case 'LIVRAISON':
                case 'PRESENTATION':
                case 'CLOTURE':
                    // Pas de tâches automatiques pour l'instant
                    break;
            }
        }

        return $tasksConfig;
    }

    /**
     * Définit l'ordre de tri par défaut pour les requêtes de projets.
     *
     * Trie les projets par date de création (les plus récents en premier).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query La requête Eloquent.
     * @return \Illuminate\Database\Eloquent\Builder La requête triée.
     */
    public function defaultSort($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Corrige et assigne les phases de projet aux tâches existantes qui n'en ont pas.
     * Utile pour la migration des anciens projets vers la nouvelle structure par phases.
     *
     * Règles d'assignation :
     * - Tâches N1 -> Phase APPRENTISSAGE
     * - Tâches N2 -> Phase PROTOTYPE
     * - Tâches N3 -> Phase REALISATION
     * - Titre 'Analyse' -> Phase ANALYSE
     * - Titre 'Conception' -> Phase CONCEPTION
     * - Titre 'Présentation' -> Phase PRESENTATION
     *
     * @param int $projetId L'identifiant du projet à corriger.
     * @return void
     */
    public function fixPhasesForExistingTasks($projetId)
    {
        // 1. Récupération des IDs des Phases Projet
        $phases = \Modules\PkgCreationTache\Models\PhaseProjet::all()->pluck('id', 'reference');

        // 2. Récupération des IDs des Phases Evaluation
        $phaseEvaluations = \Modules\PkgCompetences\Models\PhaseEvaluation::all()->pluck('id', 'code');

        // 3. Mise à jour par Niveau d'Evaluation (Prioritaire)
        if (isset($phaseEvaluations['N1']) && isset($phases['APPRENTISSAGE'])) {
            \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->where('phase_evaluation_id', $phaseEvaluations['N1'])
                ->update(['phase_projet_id' => $phases['APPRENTISSAGE']]);
        }

        if (isset($phaseEvaluations['N2']) && isset($phases['PROTOTYPE'])) {
            \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->where('phase_evaluation_id', $phaseEvaluations['N2'])
                ->update(['phase_projet_id' => $phases['PROTOTYPE']]);
        }

        if (isset($phaseEvaluations['N3']) && isset($phases['REALISATION'])) {
            \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->where('phase_evaluation_id', $phaseEvaluations['N3'])
                ->update(['phase_projet_id' => $phases['REALISATION']]);
        }

        // 4. Mise à jour par Titre (pour les tâches sans phase d'éval ou spécifiques)
        // Note : On ne surcharge pas si déjà défini, ou on force selon la logique. Ici on cible celles sans phase ou mal définies.

        if (isset($phases['ANALYSE'])) {
            \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->whereNull('phase_projet_id') // On cible celles qui restent
                ->where('titre', 'like', '%Analyse%')
                ->update(['phase_projet_id' => $phases['ANALYSE']]);
        }

        if (isset($phases['CONCEPTION'])) {
            \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->whereNull('phase_projet_id')
                ->where('titre', 'like', '%Conception%')
                ->update(['phase_projet_id' => $phases['CONCEPTION']]);
        }

        if (isset($phases['PRESENTATION'])) {
            \Modules\PkgCreationTache\Models\Tache::where('projet_id', $projetId)
                ->whereNull('phase_projet_id')
                ->where('titre', 'like', '%Présentation%')
                ->update(['phase_projet_id' => $phases['PRESENTATION']]);
        }

        // Optionnel : Cas par défaut pour tout ce qui reste -> BESOINS ou autre ?
    }

    /**
     * Calcule le barème du projet (somme des notes de ses tâches).
     *
     * @param int $projetId
     * @return float
     */
    public function getBareme(int $projetId): float
    {
        $projet = $this->model->find($projetId);
        if ($projet) {
            return (float) $projet->taches()->sum('note');
        }
        return 0.0;
    }
}
