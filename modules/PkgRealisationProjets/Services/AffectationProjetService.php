<?php

namespace Modules\PkgRealisationProjets\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Modules\Core\App\Jobs\TraitementCrudJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Services\Base\BaseAffectationProjetService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService;
use Modules\PkgRealisationTache\Services\TacheAffectationService;

/**
 * Classe AffectationProjetService pour gérer la persistance de l'entité AffectationProjet.
 */
class AffectationProjetService extends BaseAffectationProjetService
{
    protected array $index_with_relations = [
        'evaluateurs'
    ];

    public function createInstance(array $data = [])
    {
        // Récupérer l'instance par défaut via la classe parent
        $instance = parent::createInstance($data);

        // Si le projet est défini, récupérer la session de formation
        if (!empty($instance->projet_id)) {
            $projet = \Modules\PkgCreationProjet\Models\Projet::with('sessionFormation')
                ->find($instance->projet_id);

            if ($projet && $projet->sessionFormation) {
                // Initialiser date_debut si non fourni
                if (empty($instance->date_debut)) {
                    $instance->date_debut = $projet->sessionFormation->date_debut;
                }

                // Initialiser date_fin si non fourni
                if (empty($instance->date_fin)) {
                    $instance->date_fin = $projet->sessionFormation->date_fin;
                }
            }
        }

        return $instance;
    }


    /**
     * - Crée les TacheAffectations pour chaque tâche du projet
     * - Crée les RéalisationProjet pour chaque apprenant (groupe/sous-groupe)
     * - Synchronise les évaluations
     * - Met à jour la progression (cache) et le statut (running/done/error)
     *
     * @param  int    $id     ID de l'affectation projet
     * @param  string $token  Token de suivi du traitement (fourni par executeJob)
     * @return string         'done' | 'error'
     */
    public function createdObserverJob(int $id, string $token): string
        {
        try  { 
            $jobManager = new JobManager($token);
            
            // 1) Récupération de l'affectation
            $affectation = $this->find($id);

            if (!$affectation) {
                $jobManager->setError("L'affectation n'existe pas (id={$id}).");
                return 'error';
            }

            // 2) Récupération des apprenants (priorité sous-groupe)
            $apprenants = collect();
            if ($affectation?->sousGroupe) {
                $apprenants = $affectation->sousGroupe->apprenants;
            } elseif ($affectation?->groupe) {
                $apprenants = $affectation->groupe->apprenants;
            }

            if ($apprenants->isEmpty()) {
               $jobManager->setError("Aucun apprenant trouvé pour l'affectation #{$affectation->id}.");
                return 'error';
            }

            // 3) Récupération des tâches du projet
            $taches = \Modules\PkgCreationTache\Models\Tache::query()
                ->where('projet_id', $affectation->projet_id)
                ->get();

            // 4) Initialisation progression (tâches + apprenants + sync évaluation)
            $total = $taches->count() + $apprenants->count() + 1;
            $jobManager->initProgress($total);

            // 5) Services nécessaires (résolus via le conteneur)
            $tacheAffectationService = app(\Modules\PkgRealisationTache\Services\TacheAffectationService::class);
            $realisationProjetService = app(\Modules\PkgRealisationProjets\Services\RealisationProjetService::class);
            $evaluationService = app(\Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService::class);

            $jobManager->setLabel("Création des tâches");
            // 6) Création des TacheAffectations
            foreach ($taches as $tache) {
                $tacheAffectationService->create([
                    'tache_id' => $tache->id,
                    'affectation_projet_id' => $affectation->id,
                ]);
                $jobManager->tick();
            }

           
            // 7) Création des RéalisationProjet
            foreach ($apprenants as $apprenant) {

                $jobManager->setLabel("Création de réalisation de projet pour : " . $apprenant );
                $realisationProjetService->create([
                    'apprenant_id'            => $apprenant->id,
                    'affectation_projet_id'   => $affectation->id,
                    'date_debut'              => $affectation->date_debut,
                    'date_fin'                => $affectation->date_fin,
                    'rapport'                 => null,
                    'etats_realisation_projet_id' => null,
                ]);
                 $jobManager->tick();
            }

         

            // 8) Synchronisation des évaluations
            $evaluationService->SyncEvaluationRealisationProjet($affectation);
            $jobManager->tick();

            // 9) Fin OK
            $jobManager->finish(); // progress=100, status=done
            return 'done';

        } catch (\Throwable $e) {

            $jobManager->fail(function () use ($id) {
                // Suppression de l'entité si afterCreate
                $this->destroy($id);
            }, true, $e);
            return 'error';
        }
    }


public function deletedObserverJob(int $id, string $token): void
{
    $jobManager = new JobManager($token);
    $payload = $jobManager->getPayload();

    $realisation_chapitres_ids = collect($payload['realisation_chapitres_ids'] ?? []);
    $ua_ids = collect($payload['ua_ids'] ?? []);

    $total = 0;

    if ($realisation_chapitres_ids->isNotEmpty()) {
        $total++;
    }

    $total += $ua_ids->count();

    $jobManager->initProgress($total);

    // 1️⃣ Progression Chapitres
    if ($realisation_chapitres_ids->isNotEmpty()) {
        $jobManager->setLabel("🔄 Mise à jour des chapitres (N1)");
        app(\Modules\PkgApprentissage\Services\RealisationChapitreService::class)
            ->calculerProgressionDepuisRealisationChapitresIds($realisation_chapitres_ids);
        $jobManager->tick();
    }

    // 2️⃣ Progression des Unités d’Apprentissage
    if ($ua_ids->isNotEmpty()) {
        $uaService = app(\Modules\PkgApprentissage\Services\RealisationUaService::class);
        $uas = \Modules\PkgApprentissage\Models\RealisationUa::whereIn('id', $ua_ids)->get();

        foreach ($uas as $ua) {
            $jobManager->setLabel("🔄 Recalcul de la progression pour UA #{$ua->id}");
            $uaService->calculerProgression($ua);
            $jobManager->tick();
        }
    }

    $jobManager->finish();
}



    public function afterUpdateRules($affectationProjet, $id)
        {
            $realisationProjetService = new RealisationProjetService();

            $nouveauxApprenants = collect();

            if ($affectationProjet->sousGroupe) {
                $nouveauxApprenants = $affectationProjet->sousGroupe->apprenants;
            } elseif ($affectationProjet->groupe) {
                $nouveauxApprenants = $affectationProjet->groupe->apprenants;
            }

            // Récupération des réalisations existantes
            $realisationProjetService->syncApprenantsAvecRealisationProjets(
                $affectationProjet,
                $nouveauxApprenants
            );

            (new EvaluationRealisationProjetService())->SyncEvaluationRealisationProjet($affectationProjet);
        }

   

    /**
     * Affecter un projet à un groupe
     * - ajouter une Réalisation de projet pour chaque apprenant
     * @param mixed $data
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function beforCreateRules($data)
    {
        // Vérification des champs obligatoires
        if (empty($data['groupe_id']) || empty($data['projet_id'])) {
            throw new \InvalidArgumentException("Le groupe et le projet sont obligatoires.");
        }
    
        // Vérification de la cohérence des dates
        if (!empty($data['date_debut']) && !empty($data['date_fin']) && $data['date_debut'] > $data['date_fin']) {
            throw new \InvalidArgumentException("La date de début ne peut pas être après la date de fin.");
        }
    }


    /**
     * Récupère toutes les affectations de projet qui ont au moins un évaluateur.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAffectationProjetsAvecEvaluateurs()
    {
        return AffectationProjet::whereHas('evaluateurs')->get();
    }


    /**
     * Trouver la liste des affectations de projets d'un évaluateur donné.
     *
     * @param int $evaluateur_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAffectationProjetsByEvaluateurId($evaluateur_id)
    {
        return AffectationProjet::whereHas('evaluateurs', function ($query) use ($evaluateur_id) {
            $query->where('evaluateurs.id', $evaluateur_id);
        })->get();
    }
    
    /**
     * - Trouver la liste des affectations de projets d'un formateur donné.
     * - Le formateur peut être un évaluateur, il faut trouver aussi les affectation de projet 
     * dont le formateur est un évaluateur
     *
     * @param int $formateur_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    // public function getAffectationProjetsByFormateurId($formateur_id)
    // {
    //     return AffectationProjet::whereHas('projet', function ($query) use ($formateur_id) {
    //     $query->where('formateur_id', $formateur_id);
    //     })->get();
    // }
    public function getAffectationProjetsByFormateurId($formateur_id)
    {
        return AffectationProjet::where(function ($query) use ($formateur_id) {
            // Cas 1 : Le formateur est lié au projet via projets.formateur_id
            $query->whereHas('projet', function ($q) use ($formateur_id) {
                $q->where('formateur_id', $formateur_id);
            });
            // Cas 2 : Le formateur est un évaluateur via affectation_projet_evaluateur
            // ->orWhereHas('evaluateurs', function ($q) use ($formateur_id) {
            //     $q->whereHas('user', function ($subQuery) use ($formateur_id) {
            //         $subQuery->whereIn('id', function ($innerQuery) use ($formateur_id) {
            //             $innerQuery->select('user_id')
            //                     ->from('formateurs')
            //                     ->where('id', $formateur_id);
            //         });
            //     });
            // });
        })->get();
    }


    /**
     * Trouver la liste des affectations de projets d'un apprenant donné.
     *
     * @param int $apprenant_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAffectationProjetsByApprenantId($apprenant_id)
    {
        return AffectationProjet::whereHas('groupe', function ($query) use ($apprenant_id) {
            $query->whereHas('apprenants', function ($q) use ($apprenant_id) {
                $q->where('apprenants.id', $apprenant_id);
            });
        })->get();
    }


    /**
     * Récupérer la dernière affectation de projet d'un formateur en fonction de la date actuelle.
     *
     * @param int $formateur_id
     * @return AffectationProjet|null
     */
    public function getCurrentFormateurAffectation($formateur_id)
    {
        return AffectationProjet::whereHas('groupe', function ($query) use ($formateur_id) {
                $query->whereHas('formateurs', function ($q) use ($formateur_id) {
                    $q->where('formateurs.id', $formateur_id);
                });
            })
            ->where('date_debut', '<=', now()) // Date de début <= aujourd'hui
            ->where(function ($query) {
                $query->whereNull('date_fin') // Si pas de date de fin, considéré comme en cours
                    ->orWhere('date_fin', '>=', now()); // Ou date de fin >= aujourd’hui
            })
            ->orderBy('date_debut', 'desc') // Trier par date de début descendante (dernier projet en premier)
            ->first(); // Prendre le plus récent
    }

    /**
     * Récupérer la dernière affectation de projet d'un apprenant en fonction de la date actuelle.
     *
     * @param int $apprenant_id
     * @return AffectationProjet|null
     */
    public function getCurrentApprenantAffectation($apprenant_id)
    {
        return AffectationProjet::whereHas('groupe', function ($query) use ($apprenant_id) {
                $query->whereHas('apprenants', function ($q) use ($apprenant_id) {
                    $q->where('apprenants.id', $apprenant_id);
                });
            })
            ->where('date_debut', '<=', now()) // L'affectation doit avoir déjà commencé
            ->where(function ($query) {
                $query->whereNull('date_fin') // Considérer actif si pas de date de fin
                    ->orWhere('date_fin', '>=', now()); // Ou si la date de fin est dans le futur
            })
            ->orderBy('date_debut', 'desc') // Trier par la date de début la plus récente
            ->first(); // Récupérer la dernière affectation active
    }


     
     public function getDataHasEvaluateurs(string $filter, $value)
    {

    
        //  TODO : $query = $this->newQuery();
        $query = $this->allQuery(); // Créer une nouvelle requête


        // Ajouter la condition : présence d’au moins un évaluateur
        $query->whereHas('evaluateurs');


        // Construire le tableau de filtres pour la méthode `filter()`
        $filters = [$filter => $value];

        // Appliquer le filtre existant du service
        $this->filter($query, $this->model, $filters);

        return $query->get();
    }

    public function beforeDeleteRules($affectationProjet)
    {
        // Vérifier s’il existe des réalisations liées dont l'état ≠ "TODO"
        $realisationProjets = $affectationProjet->realisationProjets()->with('etatsRealisationProjet')->get();

        $hasNonTodo = $realisationProjets->contains(function ($realisation) {
            return optional($realisation->etatsRealisationProjet)->code !== 'TODO'; // état initial
        });

        if ($hasNonTodo) {
            throw new \Exception("Impossible de supprimer cette affectation : au moins une réalisation de projet a un état différent de 'À faire'. Veuillez réinitialiser tous les états à 'À faire' avant de procéder à la suppression.");
        }
    }

}
