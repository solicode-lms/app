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
     * Actions effectuées après la création d'une Mobilisation UA.
     *
     * Cette méthode orchestre les conséquences de l'ajout d'une compétence (UA) au projet :
     * 1. **Génération des Tutoriels** : Pour chaque chapitre de l'UA, une tâche de type "Tutoriel" est créée.
     *    Ces tâches sont assignées à la phase "Apprentissage" (APPRENTISSAGE) et au niveau d'évaluation N1.
     * 2. **Calcul de l'Ordre** : L'ordre des nouvelles tâches est déterminé dynamiquement :
     *    - Si des tâches existent déjà dans la phase Apprentissage, on les suit.
     *    - Sinon, on s'insère à la suite des tâches des phases précédentes (ex: après l'Analyse), 
     *      garantissant une continuité logique dans le workflow du projet.
     * 3. **Synchronisation des Apprenants** : Si des élèves travaillent déjà sur le projet, leurs réalisations
     *    sont mises à jour pour inclure ces nouvelles tâches et compétences à valider.
     *
     * @param mixed $item La mobilisation créée (instance de MobilisationUa).
     * @return void
     */
    public function afterCreateRules($item): void
    {
        if ($item instanceof \Modules\PkgCreationProjet\Models\MobilisationUa) {

            // 1. Ajouter les tâches (Tutoriels) liées aux chapitres de l'UA
            $ua = \Modules\PkgCompetences\Models\UniteApprentissage::with('chapitres')->find($item->unite_apprentissage_id);
            if ($ua && $ua->chapitres->isNotEmpty()) {

                // Récupération des IDs nécessaires
                $phaseN1Id = \Modules\PkgCompetences\Models\PhaseEvaluation::where('code', 'N1')->value('id');
                // Récupération de la phase projet "Apprentissage" via son modèle
                $phaseApprentissage = \Modules\PkgCreationTache\Models\PhaseProjet::where('reference', 'APPRENTISSAGE')->first();
                $phaseProjetId = $phaseApprentissage ? $phaseApprentissage->id : null;

                $tacheService = new \Modules\PkgCreationTache\Services\TacheService();

                foreach ($ua->chapitres as $chapitre) {
                    // Vérifier si la tâche existe déjà
                    $exists = \Modules\PkgCreationTache\Models\Tache::where('projet_id', $item->projet_id)
                        ->where('titre', 'Tutoriel : ' . $chapitre->nom)
                        ->exists();

                    if (!$exists) {
                        // Calcul de l'ordre au sein de la phase Apprentissage
                        // On prend le max ordre des tâches de cette phase pour ce projet
                        $maxOrdrePhase = 0;
                        if ($phaseProjetId) {
                            $maxOrdrePhase = \Modules\PkgCreationTache\Models\Tache::where('projet_id', $item->projet_id)
                                ->where('phase_projet_id', $phaseProjetId)
                                ->max('ordre');
                        }

                        if ($maxOrdrePhase) {
                            $ordre = $maxOrdrePhase + 1;
                        } else {
                            // Si aucune tâche dans cette phase, on prend la suite des phases précédentes
                            $maxOrdrePrecedent = 0;
                            if ($phaseApprentissage) {
                                // Récupérer les ids des phases précédentes
                                $previousPhaseIds = \Modules\PkgCreationTache\Models\PhaseProjet::where('ordre', '<', $phaseApprentissage->ordre)
                                    ->pluck('id');

                                if ($previousPhaseIds->isNotEmpty()) {
                                    $maxOrdrePrecedent = \Modules\PkgCreationTache\Models\Tache::where('projet_id', $item->projet_id)
                                        ->whereIn('phase_projet_id', $previousPhaseIds)
                                        ->max('ordre');
                                }
                            }
                            $ordre = $maxOrdrePrecedent ? $maxOrdrePrecedent + 1 : 1;
                        }

                        $tacheService->create([
                            'projet_id' => $item->projet_id,
                            'titre' => 'Tutoriel : ' . $chapitre->nom,
                            'description' => "Tutoriel lié au chapitre : " . $chapitre->nom,
                            'phase_evaluation_id' => $phaseN1Id,
                            'priorite' => 1, // Priorité par défaut
                            'ordre' => $ordre,
                            'chapitre_id' => $chapitre->id,
                            'phase_projet_id' => $phaseProjetId
                        ]);
                    }
                }
            }

            // 2. Synchroniser avec les réalisations de projet existantes (élèves)
            // Lorsqu'une nouvelle Mobilisation U.A est ajoutée à un projet, il faut mettre à jour
            // les réalisations des élèves déjà affectés à ce projet. 
            // Cela implique de créer pour eux :
            // - Les RealisationUaPrototype (pour la phase N2 prototype)
            // - Les RealisationUaProjet (pour la phase N3 projet)
            $realisationProjetService = new \Modules\PkgRealisationProjets\Services\RealisationProjetService();
            $realisationProjetService->syncRealisationsWithNewMobilisationUa($item->projet_id, $item);

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
