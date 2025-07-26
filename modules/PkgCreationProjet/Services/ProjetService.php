<?php


namespace Modules\PkgCreationProjet\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\PkgCreationProjet\Services\Base\BaseProjetService;
use Illuminate\Support\Facades\DB;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgSessions\Models\SessionFormation;

/**
 * Classe ProjetService pour gérer la persistance de l'entité Projet.
 */
class ProjetService extends BaseProjetService
{

    protected array $index_with_relations = [
        'filiere', 
        'formateur', 
        'livrables', 
        'resources', 
        'taches',
        'taches.prioriteTache',
        'affectationProjets',
        'affectationProjets.groupe'
    ];





    public function beforeUpdateRules($projet)
    {
        // Empêcher la modification de la session de formation
        if (isset($projet['session_formation_id'])) {
            $original = $this->model->find($projet['id'] ?? null);
            if ($original && $original->session_formation_id != $projet['session_formation_id']) {
                throw new \Exception('La session de formation ne peut pas être modifiée une fois le projet créé.');
            }
        }
    }

    public function afterCreateRules($projet)
    {
        $this->afterUpdateRules($projet);
    }

    public function afterUpdateRules($projet)
    {
        if (!$projet || !$projet->id) {
            return;
        }

        if ($projet->session_formation_id) {
            $session = SessionFormation::with([
                'alignementUas.uniteApprentissage.critereEvaluations.phaseEvaluation',
                'alignementUas.uniteApprentissage.chapitres'
            ])->find($projet->session_formation_id);

            if ($session) {
                $this->updateMobilisationsUa($projet, $session);
                $this->addProjectTasks($projet, $session);
            }
        }
    }

    protected function updateMobilisationsUa($projet, $session)
    {
        foreach ($session->alignementUas as $alignementUa) {
            $mobilisation = \Modules\PkgCreationProjet\Models\MobilisationUa::firstOrNew([
                'projet_id' => $projet->id,
                'unite_apprentissage_id' => $alignementUa->unite_apprentissage_id,
            ]);

            [$criteresPrototype, $baremePrototype] = $this->getCriteresEtBareme($alignementUa, 'N2');
            [$criteresProjet, $baremeProjet] = $this->getCriteresEtBareme($alignementUa, 'N3');

            $mobilisation->criteres_evaluation_prototype = $this->formatCriteres($criteresPrototype);
            $mobilisation->criteres_evaluation_projet = $this->formatCriteres($criteresProjet);
            $mobilisation->bareme_evaluation_prototype = $baremePrototype;
            $mobilisation->bareme_evaluation_projet = $baremeProjet;
            $mobilisation->description = $alignementUa->description ?? '';
            $mobilisation->save();
        }
    }

    protected function addProjectTasks($projet, $session)
    {
        $defaultTasks = ['Prototype', 'Réalisation', 'Analyse', 'Conception'];

        // Tâches pour chaque chapitre
        foreach ($session->alignementUas as $alignementUa) {
            foreach ($alignementUa->uniteApprentissage->chapitres as $chapitre) {
                Tache::firstOrCreate([
                    'projet_id' => $projet->id,
                    'titre' => 'Chapitre : ' . $chapitre->nom,
                ], [
                    'description' => $chapitre->description ?? '',
                    'reference' => (string) Str::uuid()
                ]);
            }
        }

        // Tâches par défaut
        foreach ($defaultTasks as $taskTitle) {
            Tache::firstOrCreate([
                'projet_id' => $projet->id,
                'titre' => $taskTitle,
            ], [
                'description' => $taskTitle . ' du projet',
                'reference' => (string) Str::uuid()
            ]);
        }
    }

    protected function getCriteresEtBareme($alignementUa, $niveau)
    {
        $criteres = $alignementUa->uniteApprentissage->critereEvaluations
            ->filter(fn($critere) => optional($critere->phaseEvaluation)->code === $niveau)
            ->pluck('intitule')
            ->toArray();

        $bareme = $alignementUa->uniteApprentissage->critereEvaluations
            ->filter(fn($critere) => optional($critere->phaseEvaluation)->code === $niveau)
            ->sum('bareme');

        return [$criteres, $bareme];
    }

    protected function formatCriteres(array $criteres): string
    {
        return '<ul><li>' . implode('</li><li>', $criteres) . '</li></ul>';
    }












    public function dataCalcul($projet)
    {
        // En cas de création
        if (empty($projet->id) && $projet->session_formation_id) {
            // Récupérer la session de formation liée
            $session = SessionFormation::find($projet->session_formation_id);

            if ($session) {
                // Hydrater les champs du projet avec les données de la session
                $projet->titre              = $session->titre_projet;
                $projet->travail_a_faire    = $session->description_projet;
                $projet->critere_de_travail = $session->contraintes_projet;

                // Assigner la filière si présente
                if (!empty($session->filiere_id)) {
                    $projet->filiere_id = $session->filiere_id;
                }
            }
        }

        return $projet;
    }


    public function defaultSort($query)
    {
        return $query
            ->withMax('affectationProjets', 'date_fin') // 🔥 Important
            ->orderBy('affectation_projets_max_date_fin', 'desc');
    }


    public function clonerProjet(int $projetId)
    {
        $formateurId = null;
        if (Auth::user()->hasRole('formateur')) {
            // Récupère l’id du formateur depuis la session utilisateur
            $formateurId = $this->sessionState->get('formateur_id');
            if (!$formateurId) {
                // Sécurité : si le formateur_id n’est pas en session, tu peux lever une exception ou afficher un message d’erreur
                throw new \Exception("Impossible de récupérer l'identifiant du formateur depuis la session.");
            }
        } else {
            // Gestion si l’utilisateur n’est pas formateur : lève une exception ou retourne une erreur personnalisée
            throw new \Exception("Seuls les formateurs peuvent cloner un projet.");
            // ou retourne false avec message d’erreur selon la convention de ton service
            // return false;
        }


        // On récupère le projet à cloner (avec ses relations)
        $projet = $this->model::with(['taches', 'livrables', 'resources'])->find($projetId);

        if (!$projet) {
            $this->pushServiceMessage("danger", "Clonage projet", "Projet introuvable.");
            return false;
        }

        // On encapsule tout dans une transaction
        return DB::transaction(function () use ($projet,$formateurId) {
            // Clone du projet (hors clé primaire et références uniques)
            $nouveauProjet = $projet->replicate(['id', 'reference']);
            $nouveauProjet->reference = (string) Str::uuid(); // Nouvelle référence unique
            $nouveauProjet->titre .= ' (Cloné)';
            $nouveauProjet->formateur_id = $formateurId; 
            $nouveauProjet->push(); // Insert le nouveau projet

            // -- Clonage des ressources --
            foreach ($projet->resources as $resource) {
                $newResource = $resource->replicate(['id', 'reference', 'projet_id']);
                $newResource->reference = (string) Str::uuid();
                $newResource->projet_id = $nouveauProjet->id;
                $newResource->save();
            }

            // -- Clonage des livrables --
            $livrableMap = []; // id_orig => id_clone
            foreach ($projet->livrables as $livrable) {
                $newLivrable = $livrable->replicate(['id', 'reference', 'projet_id']);
                $newLivrable->reference = (string) Str::uuid();
                $newLivrable->projet_id = $nouveauProjet->id;
                $newLivrable->save();
                $livrableMap[$livrable->id] = $newLivrable->id;
            }

            // -- Clonage des tâches --
            $tacheMap = []; // id_orig => id_clone
            foreach ($projet->taches as $tache) {
                $newTache = $tache->replicate(['id', 'reference', 'projet_id']);
                $newTache->reference = (string) Str::uuid();
                $newTache->projet_id = $nouveauProjet->id;
                $newTache->save();
                $tacheMap[$tache->id] = $newTache->id;
            }

            // -- Clonage du pivot Livrable_Tache --
            $pivotTable = DB::table('livrable_tache')
                ->whereIn('tache_id', array_keys($tacheMap))
                ->orWhereIn('livrable_id', array_keys($livrableMap))
                ->get();

            foreach ($pivotTable as $pivot) {
                // On ne clone que si les deux existent dans la nouvelle map
                if (isset($tacheMap[$pivot->tache_id]) && isset($livrableMap[$pivot->livrable_id])) {
                    DB::table('livrable_tache')->insert([
                        'tache_id' => $tacheMap[$pivot->tache_id],
                        'livrable_id' => $livrableMap[$pivot->livrable_id],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Message de succès
            $this->pushServiceMessage("success", "Clonage projet", "Le projet a été cloné avec succès.");
            return $nouveauProjet;
        });
    }

   
}
