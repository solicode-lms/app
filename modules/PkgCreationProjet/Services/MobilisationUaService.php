<?php


namespace Modules\PkgCreationProjet\Services;
use Modules\PkgCreationProjet\Services\Base\BaseMobilisationUaService;
use Modules\PkgCompetences\Models\UniteApprentissage;

/**
 * Classe MobilisationUaService pour gérer la persistance de l'entité MobilisationUa.
 */
class MobilisationUaService extends BaseMobilisationUaService
{
    /**
     * Enrichit les données de la mobilisation pour le formulaire.
     * 
     * Calcule automatiquement les critères et barèmes (Prototype & Projet)
     * si une UA est fournie.
     */
    public function dataCalcul($data)
    {
        $data = parent::dataCalcul($data);

        // Si on a une UA mais pas encore les critères calculés (ou si on veut les forcer pour l'affichage)
        if (!empty($data['unite_apprentissage_id'])) {

            $ua = UniteApprentissage::with('critereEvaluations.phaseEvaluation')->find($data['unite_apprentissage_id']);

            if ($ua) {
                [$criteresPrototype, $baremePrototype] = $this->extractCriteresEtBareme($ua, 'N2');
                [$criteresProjet, $baremeProjet] = $this->extractCriteresEtBareme($ua, 'N3');

                $data['criteres_evaluation_prototype'] = $this->formatCriteres($criteresPrototype);
                $data['criteres_evaluation_projet'] = $this->formatCriteres($criteresProjet);
                $data['bareme_evaluation_prototype'] = $baremePrototype;
                $data['bareme_evaluation_projet'] = $baremeProjet;
            }
        }

        return $data;
    }

    /**
     * Règles métier à appliquer avant la création d'une mobilisation.
     * 
     * Calcule automatiquement les critères et barèmes (Prototype & Projet)
     * si une UA est fournie mais que les champs sont vides.
     */
    public function beforeCreateRules(&$data): array
    {
        // Appel parent si nécessaire (mais BaseService n'a pas forcément de beforeCreateRules qui retourne un array)
        // Je suppose ici que je peux modifier $data et le retourner.

        // Si on a une UA mais pas encore les critères calculés
        if (!empty($data['unite_apprentissage_id']) && empty($data['criteres_evaluation_prototype'])) {

            $ua = UniteApprentissage::with('critereEvaluations.phaseEvaluation')->find($data['unite_apprentissage_id']);

            if ($ua) {
                [$criteresPrototype, $baremePrototype] = $this->extractCriteresEtBareme($ua, 'N2');
                [$criteresProjet, $baremeProjet] = $this->extractCriteresEtBareme($ua, 'N3');

                $data['criteres_evaluation_prototype'] = $this->formatCriteres($criteresPrototype);
                $data['criteres_evaluation_projet'] = $this->formatCriteres($criteresProjet);
                $data['bareme_evaluation_prototype'] = $baremePrototype;
                $data['bareme_evaluation_projet'] = $baremeProjet;
            }
        }

        return $data;
    }

    /**
     * Actions effectuées après la création d'une mobilisation.
     *
     * 1. Génère les tâches de tutoriels (N1) associées aux chapitres de l'UA.
     * 2. Synchronise les réalisations de projets existantes (élèves) avec cette nouvelle mobilisation.
     * 3. Met à jour la date de modification du projet.
     *
     * @param mixed $item La mobilisation créée.
     * @return void
     */
    public function afterCreateRules($item): void
    {
        if ($item instanceof \Modules\PkgCreationProjet\Models\MobilisationUa) {

            // 1. Ajouter les tâches (Tutoriels) liées aux chapitres de l'UA
            $ua = \Modules\PkgCompetences\Models\UniteApprentissage::with('chapitres')->find($item->unite_apprentissage_id);
            if ($ua && $ua->chapitres->isNotEmpty()) {
                $phaseN1Id = \Modules\PkgCompetences\Models\PhaseEvaluation::where('code', 'N1')->value('id');
                $tacheService = new \Modules\PkgCreationTache\Services\TacheService();

                // Calculer l'ordre/priorité max actuel pour ajouter à la suite
                // Si les compteurs sont passés dans les données "virtuelles" de l'item (non persisté), on les utilise
                // Attention : l'item est un ORM, donc ces champs n'existent pas en BDD sur MobilisationUa.
                // On peut cependant les passer via un mécanisme temporaire ou recalculer ici.

                // Correction : Le service appelant (ProjetService) s'attend à ce que l'ordre soit continu.
                // MAIS MobilisationUaService est indépendant.
                // Identifier les chapitres qui nécessitent vraiment une création de tâche
                $chapitresToAdd = $ua->chapitres->filter(function ($chapitre) use ($item) {
                    return !\Modules\PkgCreationTache\Models\Tache::where('projet_id', $item->projet_id)
                        ->where('titre', 'Tutoriel : ' . $chapitre->nom)
                        ->exists();
                });

                $count = $chapitresToAdd->count();

                if ($count > 0) {
                    // 🔍 Trouver le point d'insertion :
                    // On doit insérer APRÈS le dernier tutoriel existant (Phase N1)
                    // OU APRÈS 'Analyse' (Nature = Analyse) s'il n'y a pas encore de tutoriels

                    // 1. Chercher la dernière tâche qui correspond à l'Analyse ou aux Tutoriels existants

                    // Récupération de la configuration pour obtenir le titre exact de l'Analyse
                    $tasksConfig = \Modules\PkgCreationProjet\Services\ProjetService::getTasksConfig(null, [], []);
                    $analyseTaskTitles = [];
                    foreach ($tasksConfig as $taskData) {
                        if (is_array($taskData) && ($taskData['nature'] ?? '') === 'Analyse') {
                            $analyseTaskTitles[] = $taskData['titre'];
                        }
                    }

                    $lastPrecedingTask = \Modules\PkgCreationTache\Models\Tache::where('projet_id', $item->projet_id)
                        ->where(function ($query) use ($phaseN1Id, $analyseTaskTitles) {
                            // Soit c'est une Phase N1 (Tuto existant)
                            if ($phaseN1Id) {
                                $query->where('phase_evaluation_id', $phaseN1Id);
                            }
                            // Soit c'est une tâche de nature 'Analyse' (selon la config)
                            if (!empty($analyseTaskTitles)) {
                                $query->orWhereIn('titre', $analyseTaskTitles);
                            }
                        })
                        ->orderBy('ordre', 'desc')
                        ->first();

                    if ($lastPrecedingTask) {
                        $insertionPointOrdre = $lastPrecedingTask->ordre + 1;
                        $insertionPointPriorite = $lastPrecedingTask->priorite + 1;
                    } else {
                        // Fallback (ne devrait pas arriver si Analyse existe)
                        $insertionPointOrdre = 1;
                        $insertionPointPriorite = 1;
                    }

                    // 🔼 DÉCALER les tâches qui sont APRÈS ce point (Prototype, Conception, etc.)
                    \Modules\PkgCreationTache\Models\Tache::where('projet_id', $item->projet_id)
                        ->where('ordre', '>=', $insertionPointOrdre)
                        ->increment('ordre', $count);

                    \Modules\PkgCreationTache\Models\Tache::where('projet_id', $item->projet_id)
                        ->where('priorite', '>=', $insertionPointPriorite)
                        ->increment('priorite', $count);


                    $currentOrdre = $insertionPointOrdre;
                    $currentPriorite = $insertionPointPriorite;

                    // 📝 Création et insertion des tâches
                    foreach ($chapitresToAdd as $chapitre) {
                        $tacheService->create([
                            'projet_id' => $item->projet_id,
                            'titre' => 'Tutoriel : ' . $chapitre->nom,
                            'description' => $chapitre->description ?? '',
                            'priorite' => $currentPriorite,
                            'ordre' => $currentOrdre,
                            'phase_evaluation_id' => $phaseN1Id,
                            'chapitre_id' => $chapitre->id
                        ]);

                        $currentOrdre++;
                        $currentPriorite++;
                    }
                }
            }

            // 2. Synchroniser avec les réalisations de projet existantes (élèves)
            // Lorsqu'une nouvelle Mobilisation U.A est ajoutée à un projet, il faut mettre à jour
            // les réalisations des élèves déjà affectés à ce projet. 
            // Cela implique de créer pour eux :
            // - Les RealisationUaPrototype (pour la phase N2 prototype)
            // - Les RealisationUaProjet (pour la phase N3 projet)
            // ceci est géré par la méthode addMobilisationToProjectRealisations.
            $realisationProjetService = new \Modules\PkgRealisationProjets\Services\RealisationProjetService();
            $realisationProjetService->addMobilisationToProjectRealisations($item->projet_id, $item);

            // 3. Mise à jour de la date de modification du projet parent
            if (isset($item->projet)) {
                $item->projet->touch();
            }
        }
    }

    public function afterUpdateRules($item): void
    {
        if ($item instanceof \Modules\PkgCreationProjet\Models\MobilisationUa && isset($item->projet)) {
            $item->projet->touch();
        }
    }

    public function destroy($id)
    {
        $mobilisation = $this->find($id);

        if ($mobilisation) {
            // 1. Supprimer les tâches associées (TOUTES les tâches liées aux chapitres de l'UA)
            $ua = \Modules\PkgCompetences\Models\UniteApprentissage::with('chapitres')->find($mobilisation->unite_apprentissage_id);

            if ($ua && $ua->chapitres->isNotEmpty()) {
                $chapitreIds = $ua->chapitres->pluck('id');

                // Supprimer TOUTES les tâches liées à ces chapitres pour ce projet
                \Modules\PkgCreationTache\Models\Tache::where('projet_id', $mobilisation->projet_id)
                    ->whereIn('chapitre_id', $chapitreIds)
                    ->delete();
            }

            // 2. Nettoyer les réalisations de projet
            $realisationProjetService = new \Modules\PkgRealisationProjets\Services\RealisationProjetService();
            $realisationProjetService->removeMobilisationFromProjectRealisations(
                $mobilisation->projet_id,
                $mobilisation->unite_apprentissage_id
            );
        }

        $result = parent::destroy($id);

        if ($mobilisation) {
            // Mise à jour de la date de modification du projet parent
            if (isset($mobilisation->projet)) {
                $mobilisation->projet->touch();
            }
        }

        return $result;
    }

    /**
     * Extrait les critères et le barème depuis une UA pour un niveau donné.
     */
    protected function extractCriteresEtBareme($ua, $niveau)
    {
        $criteres = $ua->critereEvaluations
            ->filter(fn($critere) => optional($critere->phaseEvaluation)->code === $niveau)
            ->pluck('intitule')
            ->toArray();

        $bareme = $ua->critereEvaluations
            ->filter(fn($critere) => optional($critere->phaseEvaluation)->code === $niveau)
            ->sum('bareme');

        return [$criteres, $bareme];
    }

    /**
     * Formate une liste de critères en HTML.
     */
    protected function formatCriteres(array $criteres): string
    {
        if (empty($criteres))
            return '';
        return '<ul><li>' . implode('</li><li>', $criteres) . '</li></ul>';
    }
}
