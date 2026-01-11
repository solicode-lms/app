<?php


namespace Modules\PkgCreationProjet\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\PkgCreationProjet\Services\Base\BaseProjetService;
use Illuminate\Support\Facades\DB;
use Modules\PkgCompetences\Models\PhaseEvaluation;
use Modules\PkgCreationProjet\Models\Livrable;
use Modules\PkgCreationProjet\Models\NatureLivrable;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Modules\PkgSessions\Models\SessionFormation;
use Modules\Core\App\Exceptions\BlException;

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
        'affectationProjets',
        'affectationProjets.groupe'
    ];



    /**
     * Crée une instance de Projet.
     *
     * @param array $data Données initiales.
     * @return mixed L'instance créée.
     * @throws BlException Si l'ID du formateur ne peut pas être récupéré.
     */
    public function createInstance(array $data = [])
    {
        // Si l'utilisateur est formateur, on injecte son formateur_id
        if (Auth::check() && Auth::user()->hasRole('formateur')) {
            // Récupération sécurisée du formateur_id depuis la session
            $formateurId = $this->sessionState->get('formateur_id');

            if (!$formateurId) {
                throw new BlException("Impossible de récupérer l'identifiant du formateur depuis la session.");
            }

            $data['formateur_id'] = $formateurId;
        }

        return parent::createInstance($data);
    }

    /**
     * Crée un nouveau projet.
     * 
     * Cette méthode surcharge la méthode parente pour garantir que si l'utilisateur connecté
     * est un formateur, le projet lui est automatiquement assigné via son ID récupéré en session.
     *
     * @param array|object $data Données du projet.
     * @return mixed Le projet créé.
     * @throws \Exception Si l'ID du formateur ne peut pas être récupéré pour un formateur connecté.
     */
    public function create(array|object $data)
    {
        // Vérifier si l'utilisateur connecté est un formateur
        if (Auth::check() && Auth::user()->hasRole('formateur')) {
            // Récupération sécurisée du formateur_id depuis la session
            $formateurId = $this->sessionState->get('formateur_id');

            if (!$formateurId) {
                throw new \Exception("Impossible de récupérer l'identifiant du formateur depuis la session.");
            }

            // Forcer la valeur, peu importe ce qui est envoyé par le client
            if (is_array($data)) {
                $data['formateur_id'] = $formateurId;
            } elseif (is_object($data)) {
                $data->formateur_id = $formateurId;
            }
        }

        return parent::create($data);
    }


    /**
     * Vérifie les règles métier avant la suppression d'un projet.
     *
     * Empêche la suppression si le projet est déjà affecté à des groupes
     * pour garantir l'intégrité des données historiques.
     *
     * @param mixed $projet Le projet à supprimer.
     * @throws BlException Si le projet a des affectations actives.
     * @return void
     */
    public function beforeDeleteRules($projet)
    {
        // Vérification des affectations liées au projet
        $affectations = $projet->affectationProjets()->count();

        if ($affectations > 0) {
            throw new BlException("Impossible de supprimer ce projet : </br> il est encore affecté à un ou plusieurs groupes. </br> Supprimez d'abord les affectations avant de supprimer le projet.");
        }
    }


    /**
     * Vérifie les règles métier avant la mise à jour d'un projet.
     *
     * Interdit la modification de la session de formation une fois
     * que celle-ci a été définie lors de la création.
     *
     * @param array $projet Les données du projet à mettre à jour.
     * @throws BlException Si on tente de changer la session de formation.
     * @return void
     */
    public function beforeUpdateRules($projet)
    {
        // Empêcher la modification de la session de formation
        if (isset($projet['session_formation_id'])) {
            $original = $this->model->find($projet['id'] ?? null);
            if ($original && $original->session_formation_id != $projet['session_formation_id']) {
                throw new BlException('La session de formation ne peut pas être modifiée une fois le projet créé.');
            }
        }
    }

    /**
     * Exécute les actions nécessaires après la création d'un projet.
     *
     * Cette méthode orchestre l'initialisation du projet :
     * - Importation des compétences (mobilisations UA) depuis la session.
     * - Création automatique de l'arbre des tâches (Analyse, Tutos, Prototype, etc.).
     * - Ajout des livrables par défaut.
     *
     * @param mixed $projet Le projet fraîchement créé.
     * @return void
     */
    public function afterCreateRules($projet)
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

        // 🔹 Ajout des livrables par défaut
        $this->addDefaultLivrables($projet);


    }

    /**
     * Point d'ancrage pour les règles métier après mise à jour.
     *
     * @param mixed $projet Le projet mis à jour.
     * @return void
     */
    public function afterUpdateRules($projet)
    {

    }

    /**
     * Met à jour ou initialise les mobilisations des Unités d'Apprentissage (UA).
     *
     * Associe les UA de la session au projet et copie les critères 
     * d'évaluation (Prototype N2 et Projet N3) pour figer le référentiel.
     *
     * @param mixed $projet Le projet concerné.
     * @param mixed $session La session de formation source.
     * @return void
     */
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


    /**
     * Génère et ajoute les tâches du projet basées sur le scénario pédagogique.
     *
     * Crée une séquence de tâches standardisée :
     * 1. Analyse
     * 2. Tutoriels (basés sur les chapitres de la session) - Niveau N1
     * 3. Prototype - Niveau N2
     * 4. Conception
     * 5. Réalisation - Niveau N3
     *
     * @param mixed $projet Le projet cible.
     * @param mixed $session La session contenant la structure pédagogique.
     * @return void
     */
    protected function addProjectTasks($projet, $session)
    {
        $priorite = 1; // compteur de priorité progressive
        $ordre = 1;   // compteur d'ordre

        // Récupérer les IDs des phases d'évaluation (N1, N2, N3)
        $phaseN1 = PhaseEvaluation::where('code', 'N1')->value('id');
        $phaseN2 = PhaseEvaluation::where('code', 'N2')->value('id');
        $phaseN3 = PhaseEvaluation::where('code', 'N3')->value('id');

        // Calculer la note pour le prototype et la réalisation
        $notePrototype = $session->alignementUas->sum(function ($alignementUa) {
            return $alignementUa->uniteApprentissage->critereEvaluations
                ->filter(fn($critere) => optional($critere->phaseEvaluation)->code === 'N2')
                ->sum('bareme');
        });

        $noteRealisation = $session->alignementUas->sum(function ($alignementUa) {
            return $alignementUa->uniteApprentissage->critereEvaluations
                ->filter(fn($critere) => optional($critere->phaseEvaluation)->code === 'N3')
                ->sum('bareme');
        });

        // Tâche Analyse
        Tache::firstOrCreate(
            [
                'projet_id' => $projet->id,
                'titre' => 'Analyse',
            ],
            [
                'description' => 'Analyse du projet',
                'priorite' => $priorite++,
                'ordre' => $ordre++,
                'phase_evaluation_id' => null,
                'chapitre_id' => null
            ]
        );

        // Tâches Chapitre
        foreach ($session->alignementUas as $alignementUa) {
            foreach ($alignementUa->uniteApprentissage->chapitres as $chapitre) {
                Tache::firstOrCreate(
                    [
                        'projet_id' => $projet->id,
                        'titre' => 'Tutoriel : ' . $chapitre->nom,
                    ],
                    [
                        'description' => $chapitre->description ?? '',
                        'priorite' => $priorite++,
                        'ordre' => $ordre++,
                        'phase_evaluation_id' => $phaseN1,
                        'chapitre_id' => $chapitre->id
                    ]
                );
            }
        }

        // Tâche Prototype
        Tache::firstOrCreate(
            [
                'projet_id' => $projet->id,
                'titre' => $session->titre_prototype ? "Prototype : " . $session->titre_prototype : 'Prototype',
            ],
            [
                'description' => trim(($session->description_prototype ?? '') . "</br><b>Contraintes</b>" . ($session->contraintes_prototype ?? '')),
                'priorite' => $priorite++,
                'ordre' => $ordre++,
                'phase_evaluation_id' => $phaseN2,
                'chapitre_id' => null,
                'is_live_coding_task' => false,
                'note' => $notePrototype
            ]
        );

        // Tâche Conception
        Tache::firstOrCreate(
            [
                'projet_id' => $projet->id,
                'titre' => 'Conception',
            ],
            [
                'description' => 'Conception du projet',
                'priorite' => $priorite++,
                'ordre' => $ordre++,
                'phase_evaluation_id' => null,
                'chapitre_id' => null
            ]
        );

        // Tâche Réalisation
        Tache::firstOrCreate(
            [
                'projet_id' => $projet->id,
                'titre' => 'Réalisation',
            ],
            [
                'description' => trim(($session->description_projet ?? '') . "</br><b>Contraintes</b>" . ($session->contraintes_projet ?? '')),
                'priorite' => $priorite++,
                'ordre' => $ordre++,
                'phase_evaluation_id' => $phaseN3,
                'chapitre_id' => null,
                'is_live_coding_task' => false,
                'note' => $noteRealisation
            ]
        );
    }

    /**
     * Extrait les critères d'évaluation et calcule le barème pour un niveau donné.
     *
     * @param mixed $alignementUa L'alignement UA contenant l'unité d'apprentissage.
     * @param string $niveau Le code du niveau d'évaluation (ex: 'N2', 'N3').
     * @return array Un tableau contenant [liste_criteres (array), total_bareme (float)].
     */
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

    /**
     * Formate une liste de critères en HTML.
     *
     * @param array $criteres Liste des chaînes de caractères des critères.
     * @return string Liste HTML non ordonnée (<ul>).
     */
    protected function formatCriteres(array $criteres): string
    {
        return '<ul><li>' . implode('</li><li>', $criteres) . '</li></ul>';
    }

    /**
     * Enrichit l'objet projet avec des données calculées ou par défaut.
     *
     * Lors de l'initialisation (création), pré-remplit le titre, la description 
     * et les contraintes à partir de la session de formation sélectionnée.
     *
     * @param mixed $data Les données brutes ou l'objet projet.
     * @return mixed L'objet projet enrichi.
     */
    public function dataCalcul($data)
    {
        $projet = parent::dataCalcul($data);
        // En cas de création
        if (empty($projet->id) && $projet->session_formation_id) {
            // Récupérer la session de formation liée
            $session = SessionFormation::find($projet->session_formation_id);

            if ($session) {
                // Hydrater les champs du projet avec les données de la session
                $projet->titre = $session->titre_projet;
                $projet->travail_a_faire = $session->description_projet;
                $projet->critere_de_travail = $session->contraintes_projet;

                // Assigner la filière si présente
                if (!empty($session->filiere_id)) {
                    $projet->filiere_id = $session->filiere_id;
                }
            }
        }

        return $projet;
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
    public function defaultSort($query)
    {
        return $query
            ->withMax('affectationProjets', 'date_fin') // 🔥 Important
            ->orderBy('affectation_projets_max_date_fin', 'desc');
    }

    /**
     * Clone un projet complet pour le formateur connecté.
     *
     * Duplique le projet et toutes ses dépendances :
     * - Ressources
     * - Livrables
     * - Tâches
     * - Relations Livrables-Tâches
     *
     * @param int $projetId L'ID du projet source.
     * @return mixed Le nouveau projet cloné ou false en cas d'erreur.
     * @throws BlException Si l'utilisateur n'est pas autorisé.
     * @throws \Exception Si l'ID formateur est introuvable.
     */
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
            throw new BlException("Seuls les formateurs peuvent cloner un projet.");
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
        return DB::transaction(function () use ($projet, $formateurId) {
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

    /**
     * Ajoute les livrables par défaut à un projet.
     * 
     * Crée automatiquement les entrées pour "Code source" et "Présentation"
     * en se basant sur les références de nature de livrable.
     *
     * @param mixed $projet Le projet cible.
     * @return void
     */
    protected function addDefaultLivrables($projet)
    {
        $defaultLivrables = [
            [
                'titre' => 'Code source',
                'description' => 'Livrable contenant le code source complet du projet',
                'natureReference' => 'Code'
            ],
            [
                'titre' => 'Présentation',
                'description' => 'Présentation du projet (slides, vidéo, etc.)',
                'natureReference' => 'Présentation'
            ],
        ];

        foreach ($defaultLivrables as $livrableData) {
            // Récupérer l’ID de la nature correspondant à la référence
            $natureId = NatureLivrable::where('reference', $livrableData['natureReference'])->value('id');

            Livrable::firstOrCreate(
                [
                    'projet_id' => $projet->id,
                    'titre' => $livrableData['titre'],
                ],
                [
                    'description' => $livrableData['description'],
                    'nature_livrable_id' => $natureId, // null si introuvable
                ]
            );
        }
    }
}
