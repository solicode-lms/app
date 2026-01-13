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
 */
class ProjetService extends BaseProjetService
{
    use ProjetActionsTrait, ProjetCalculTrait, ProjetRelationsTrait, ProjetCrudTrait;

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
     * Retourne la configuration des tâches à générer pour un projet donné.
     * Cette configuration définit l'ordre et les propriétés des tâches en fonction
     * des phases de projet définies en base de données.
     *
     * @param mixed $session La session de formation (pour les titres/descriptions dynamiques).
     * @param array $phasesEval Les IDs des phases d'évaluation ['N1' => id, 'N2' => id, 'N3' => id].
     * @param array $notes Les notes calculées ['prototype' => float, 'realisation' => float].
     * @return array
     */
    public static function getTasksConfig($session, $phasesEval, $notes)
    {
        $tasksConfig = [];
        $phasesProjet = \Modules\PkgCreationProjet\Models\PhaseProjet::orderBy('ordre')->get();

        foreach ($phasesProjet as $phase) {
            switch ($phase->reference) {
                case 'PH_ANALYSE':
                    $tasksConfig[] = [
                        'nature' => 'Analyse',
                        'titre' => 'Analyse',
                        'description' => 'Analyse du projet',
                        'phase_evaluation_id' => null,
                        'note' => null,
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'PH_APPRENTISSAGE':
                    // Marqueur pour insertion dynamique des tutoriels
                    // On conserve la clé spécique ou un tableau spécial
                    $tasksConfig[] = [
                        'type' => 'Tutoriels',
                        'phase_projet_id' => $phase->id, // Pour que les tutos aient cette phase
                    ];
                    break;

                case 'PH_REALISATION': // Prototype ? Ou Réalisation ?
                    // En fonction de l'ordre, si c'est avant conception c'est louche, mais suivons le standard
                    // Si on a Prototype et Réalisation séparés dans les phases, on les mappe.
                    // Mais ici on n'a que PH_REALISATION dans le CSV de base.

                    // Attend, dans le CSV précédent on avait PH_REALISATION et PH_CONCEPTION.
                    // PH_CONCEPTION (4), PH_REALISATION (5).

                    // PROTOTYPE : C'est souvent lié à la réalisation technique du N2.
                    // Si on suit le getTasksConfig précédent : Prototype (N2) puis Conception puis Réalisation (N3).

                    // SI PH_CONCEPTION existe, on met Conception dedans.
                    // SI PH_REALISATION existe, on met Réalisation et Prototype dedans ? 

                    // Pour coller à l'ancien getTasksConfig :
                    // Prototype était avant Conception. Maintenant PH_CONCEPTION est en 4, PH_REALISATION en 5.
                    // Le Prototype devrait peut-être être dans PH_CONCEPTION (maquette technique) ou PH_REALISATION ?
                    // Souvent Prototype = PH_REALISATION (première version).

                    // Si on a PH_REALISATION, on ajoute Prototype ET Réalisation ? 
                    // Non, ce sont des tâches distinctes.
                    // Le code précédent :
                    // 1. Analyse (PH_ANALYSE)
                    // 2. MOBILISATIONS (PH_APPRENTISSAGE)
                    // 3. Prototype (N2)
                    // 4. Conception (PH_CONCEPTION)
                    // 5. Réalisation (N3) (PH_REALISATION)

                    // Le Prototype ne semble pas avoir de phase projet dédiée dans le CSV actuel.
                    // On va l'associer à PH_REALISATION par défaut, ou PH_CONCEPTION ?

                    // Modifions la logique :
                    // On ajoute Prototype explicitement s'il n'est pas couvert.
                    // Mais l'utilisateur veut utiliser les phasesProjet.

                    // Disons que :
                    // PH_REALISATION contient Prototype (N2) ET Réalisation finale (N3) ?
                    // Ou alors on ajoute le Prototype dans la phase qui semble la plus logique : APPRENTISSAGE ou REALISATION ?

                    // Dans les projets pédagogiques type Solicode :
                    // Prototype = Preuve de concept technique. C'est de la Realisation.

                    // On va ajouter Prototype et Réalisation dans PH_REALISATION pour l'instant, 
                    // sauf si on peut les distinguer par ordre.

                    // LE SOUCI : getTasksConfig itère sur les phases.
                    // Si on est dans PH_REALISATION, on ajoute les deux ?

                    $tasksConfig[] = [
                        'nature' => 'Réalisation',
                        'titre' => optional($session)->titre_prototype ? "Prototype : " . optional($session)->titre_prototype : 'Prototype',
                        'description' => trim((optional($session)->description_prototype ?? '') . "</br><b>Contraintes</b>" . (optional($session)->contraintes_prototype ?? '')),
                        'phase_evaluation_id' => $phasesEval['N2'] ?? null,
                        'note' => $notes['prototype'] ?? 0,
                        'phase_projet_id' => $phase->id,
                    ];

                    $tasksConfig[] = [
                        'nature' => 'Réalisation',
                        'titre' => 'Réalisation',
                        'description' => trim((optional($session)->description_projet ?? '') . "</br><b>Contraintes</b>" . (optional($session)->contraintes_projet ?? '')),
                        'phase_evaluation_id' => $phasesEval['N3'] ?? null,
                        'note' => $notes['realisation'] ?? 0,
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'PH_CONCEPTION':
                    $tasksConfig[] = [
                        'nature' => 'Conception',
                        'titre' => 'Conception',
                        'description' => 'Conception du projet',
                        'phase_evaluation_id' => null,
                        'note' => null,
                        'phase_projet_id' => $phase->id,
                    ];
                    break;

                case 'PH_BESOINS':
                    // Optionnel, si on veut une tâche Besoins
                    break;

                case 'PH_LIVRAISON':
                case 'PH_PRESENTATION':
                case 'PH_CLOTURE':
                    // Pas de tâches automatiques pour l'instant
                    break;
            }
        }

        return $tasksConfig;
    }

    /**
     * Définit l'ordre de tri par défaut pour les requêtes de projets.
     *
     * Trie les projets par la date de fin la plus récente de leurs affectations,
     * mettant en avant les projets actifs ou récemment terminés.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query La requête Eloquent.
     * @return \Illuminate\Database\Eloquent\Builder La requête triée.
     */
    // public function defaultSort($query)
    // {
    //     return $query
    //         ->withMax('affectationProjets', 'date_fin') // 🔥 Important
    //         ->orderBy('affectation_projets_max_date_fin', 'asc');
    // }
}
