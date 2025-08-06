<?php

namespace Modules\PkgRealisationTache\Services\RealisationTacheService;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgAutorisation\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Modules\PkgRealisationTache\Database\Seeders\EtatRealisationTacheSeeder;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Illuminate\Database\Eloquent\Builder;
use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Modules\PkgApprentissage\Models\RealisationChapitre;
use Modules\PkgCompetences\Services\ChapitreService;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgRealisationTache\Services\HistoriqueRealisationTacheService;
use Modules\PkgEvaluateurs\Services\EvaluationRealisationTacheService;

trait RealisationTacheServiceCrud
{

    /**
     * Méthode contient les règles métier qui sont appliquer avant l'édition
     * il est utilisée avec tous les méthode qui font update
     * @param mixed $entity
     * @param array $data
     * @return void
     */
    public function beforeUpdateRules(array &$data, $id){
        
        $entity = $this->find($id);


        // ❌ Bloquer l'état si la tâche a des livrables mais aucun n'est encore déposé
        // Il test si $etat est null
        // Il ne l'applique pas au formateur
        if (
            !Auth::user()->hasRole(Role::FORMATEUR_ROLE) &&
            isset($data["etat_realisation_tache_id"]) &&
            ($etat = EtatRealisationTache::find($data["etat_realisation_tache_id"]))
        ) {
            $etatCode = $etat->workflowTache?->code;
            $etatsInterdits = ['EN_COURS', 'EN_VALIDATION', 'TERMINEE'];

            $tache = $entity->tache;

            if ($tache->livrables()->exists()) {
                $livrables = $tache->livrables;
                $idsLivrables = $livrables->pluck('id');

                // Récupère les IDs des livrables déjà déposés
                $idsLivrablesDeposes = $entity->realisationProjet
                    ->livrablesRealisations()
                    ->whereIn('livrable_id', $idsLivrables)
                    ->pluck('livrable_id');

                // Filtre les livrables non encore déposés
                $livrablesManquants = $livrables->filter(function ($livrable) use ($idsLivrablesDeposes) {
                    return !$idsLivrablesDeposes->contains($livrable->id);
                });

                if ($livrablesManquants->isNotEmpty() && in_array($etatCode, $etatsInterdits)) {
                    $nomsLivrables = $livrablesManquants->pluck('titre')->filter()->map(function ($titre) {
                        return "<li>" . e($titre) . "</li>";
                    })->join('');

                    $message = "<p>Impossible de passer à l’état « {$etat->nom} », </br> les livrables suivants sont requis mais non déposés :</p><ul>{$nomsLivrables}</ul>";

                    throw ValidationException::withMessages([
                        'etat_realisation_tache_id' => $message
                    ]);
                }
            }
        }



        // Empêcher un apprenant d'affecter un état réservé aux formateurs
        if (!Auth::user()->hasRole(Role::FORMATEUR_ROLE) && !empty($data["etat_realisation_tache_id"])) {
            $etat_realisation_tache_id = $data["etat_realisation_tache_id"];
            $nouvelEtat = EtatRealisationTache::find($etat_realisation_tache_id);

            // Vérifier si le nouvel état existe
            if ($nouvelEtat) {
                if ($nouvelEtat->is_editable_only_by_formateur && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
                    throw ValidationException::withMessages([
                        'etat_realisation_tache_id' => "Seul un formateur peut affecter cet état de tâche."
                    ]);
                }
                // ✅ Vérifie le respect de la priorité selon le workflow
                $workflowCode = optional($nouvelEtat->workflowTache)->code;
                if ($this->workflowExigeRespectDesPriorites($workflowCode)) {
                    $this->verifierTachesMoinsPrioritairesTerminees($entity,$workflowCode);
                }
            }

            // Vérification si l'état actuel existe et est modifiable uniquement par un formateur
            if ($entity->etatRealisationTache) {
                if (
                    $entity->etatRealisationTache->is_editable_only_by_formateur
                    && $entity->etatRealisationTache->id != $etat_realisation_tache_id
                    && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)
                ) {
                    throw ValidationException::withMessages([
                        'etat_realisation_tache_id' => "Cet état de projet doit être modifié par le formateur."
                    ]);
                }
            }
        }

        if(Auth::user()->hasRole(Role::FORMATEUR_ROLE)){
                // Si des évaluateurs existent, s'assurer que l'utilisateur y figure
                $user = Auth::user();
                $entity = $this->find($id);
                // Récupère les évaluateurs assignés au projet
                $evaluateurs = $entity
                    ->realisationProjet
                    ->affectationProjet
                    ->evaluateurs
                    ->pluck('id');

                
                if ($evaluateurs->isNotEmpty() 
                    && $evaluateurs->doesntContain($user->evaluateur->id)
                ) {
                    throw new Exception("Le formateur n'est pas parmi les évaluateurs de ce projet.");
                }
        }
       

    
           // Historique des modification
        $historiqueRealisationTacheService = new HistoriqueRealisationTacheService();
        $historiqueRealisationTacheService->enregistrerChangement($entity,$data);
        $this->mettreAJourEtatRevisionSiRemarqueModifiee($entity, $data);
        

    }


    /**
     * affectation de dataDebut = now()
     * @param int $id
     */
    public function afterEditRules($entity, $id)
    {
        if (is_null($entity->dateDebut)) {
            $entity->dateDebut = now()->toDateString(); // format YYYY-MM-DD sans heure
            $entity->save(); // il faut sauvegarder si tu veux que le changement soit persisté
        }

        // Déja appliquer par parrent
        // $this->markNotificationsAsRead( $entity->id);
    }

  
    

    /**
     * Trie pardéfaut
     * 1️⃣ Trier par date de fin de l'affectation
     * 2️⃣ Ensuite par ordre de tâche
     * @param mixed $query
     */
    public function defaultSort($query)
    {
        return $query
            // ->with(['realisationProjet.affectationProjet']) // Charger affectationProjet
            ->join('realisation_projets', 'realisation_taches.realisation_projet_id', '=', 'realisation_projets.id')
            ->join('affectation_projets', 'realisation_projets.affectation_projet_id', '=', 'affectation_projets.id')
            ->join('taches', 'realisation_taches.tache_id', '=', 'taches.id')
            ->orderBy('affectation_projets.date_fin', 'desc') // 1️⃣ Trier par date de fin de l'affectation
            ->orderBy('taches.ordre', 'asc') // 2️⃣ Ensuite par ordre de tâche
            ->select('realisation_taches.*'); // 🎯 Important pour éviter le problème de Model::hydrate
    }


    public function afterUpdateRules(RealisationTache $entity): void
    {
        // 1️⃣ Mettre à jour les chapitres si l’état a changé
        if ($entity->wasChanged('etat_realisation_tache_id')) {
            $this->synchroniserEtatsChapitreDepuisTache($entity);


            // 2️⃣ Recalculer les UA concernées
            $uaIds = collect();

            $entity->loadMissing(['realisationUaPrototypes', 'realisationUaProjets']);

            foreach ($entity->realisationUaPrototypes as $proto) {
                if ($proto->realisation_ua_id) {
                    $uaIds->push($proto->realisation_ua_id);
                }
            }

            foreach ($entity->realisationUaProjets as $projet) {
                if ($projet->realisation_ua_id) {
                    $uaIds->push($projet->realisation_ua_id);
                }
            }

            $uaIds = $uaIds->unique()->filter();

            if ($uaIds->isNotEmpty()) {
                $service = new \Modules\PkgApprentissage\Services\RealisationUaService();
                $uas = \Modules\PkgApprentissage\Models\RealisationUa::whereIn('id', $uaIds)->get();

                foreach ($uas as $ua) {
                    $service->calculerProgressionEtNote($ua);
                }
            }

          

        }

        if ($entity->wasChanged('note')) {
            // 3️⃣ Répartir la note sur les prototypes associés
            $this->repartirNoteDansRealisationUaPrototypes($entity);
        }
          

        
    }


    /**
     * Met à jour les états des chapitres liés à une tâche lorsque son état change.
     *
     * @param RealisationTache $tache
     * @return void
     */
    private function synchroniserEtatsChapitreDepuisTache(RealisationTache $tache): void
    {
        $chapitres = RealisationChapitre::where('realisation_tache_id', $tache->id)->get();

        if ($chapitres->isEmpty()) {
            return;
        }

        $etatChapitre = $this->mapEtatTacheToEtatChapitre($tache->etat_realisation_tache_id);

        if (!$etatChapitre) {
            return;
        }

        $realisationChapitreService = new RealisationChapitreService();

        foreach ($chapitres as $chapitre) {
            $realisationChapitreService->update($chapitre->id, [
                'etat_realisation_chapitre_id' => $etatChapitre->id,
            ]);
        }
    }

    /**
     * Mapper un état de tâche à un état de chapitre
     */
    private function mapEtatTacheToEtatChapitre(int $etatTacheId)
    {
        $etatTache = EtatRealisationTache::with('workflowTache')->find($etatTacheId);

        if (!$etatTache || !$etatTache->workflowTache) {
            return null;
        }

        // Table de mapping entre les codes
        $mapping = [
            'TODO'            => 'TODO',
            'IN_PROGRESS'           => 'IN_PROGRESS',
            'PAUSED'           => 'PAUSED',
            'REVISION_NECESSAIRE'=> 'IN_PROGRESS',
            'READY_FOR_LIVE_CODING' => 'READY_FOR_LIVE_CODING',
            'IN_LIVE_CODING' => 'IN_LIVE_CODING',
            'TO_APPROVE'      => 'TO_APPROVE',
            'DONE'           => 'DONE',
            'BLOCKED' => 'BLOCKED'
        ];

        $codeChapitre = $mapping[$etatTache->workflowTache->code] ?? null;

        if (!$codeChapitre) {
            return null;
        }

        return EtatRealisationChapitre::where('code', $codeChapitre)->first();
    }


    


    /**
     * Répartit la note de la tâche sur les RealisationUaPrototypes associés,
     * en fonction du taux de remplissage (note / barème),
     * tout en respectant les barèmes et en arrondissant à 0.25.
     *
     * ✅ À la fin, la somme exacte des notes des prototypes sera égale à la note de la tâche.
     *
     * 🔢 Exemple :
     *  - P1 = 3 / 5  → taux = 0.6
     *  - P2 = 3 / 6  → taux = 0.5
     *  - total taux = 1.1
     *  - Ratio P1 = 0.6 / 1.1 ≈ 0.5455
     *  - Ratio P2 = 0.5 / 1.1 ≈ 0.4545
     *  - Pour une note globale de 5 :
     *      P1 ≈ 2.73 → arrondi à 2.75
     *      P2 ≈ 2.27 → arrondi à 2.25
     */
    public function repartirNoteDansRealisationUaPrototypes(RealisationTache $entity): void
    {
        $prototypes = $entity->realisationUaPrototypes;

        if ($prototypes->isEmpty() || $entity->note === null) {
            return;
        }

        // ✅ Définition de la constante d’arrondi
        $STEP_ROUNDING = 0.5;

        // ⚠️ Ne garder que les prototypes avec un barème > 0
        $prototypes = $prototypes->filter(fn($p) => $p->bareme > 0);
        if ($prototypes->isEmpty()) return;

        // 🧮 Fonction pour arrondir à un multiple de 0.25
        $roundToStep =  fn($value) => round($value / $STEP_ROUNDING) * $STEP_ROUNDING;

        // 🎯 Étape 1 : calcul du total des taux de remplissage (note actuelle / barème)
        $totalRemplissage = $prototypes->sum(function ($p) {
            $note = $p->note ?? 0;
            return $note / $p->bareme;
        });

        // Si aucun taux valide → on sort
        if ($totalRemplissage <= 0) return;

        $noteTotale = $entity->note;
        $repartitions = [];

        // 1️⃣ Répartition initiale avec arrondi à 0.25
        $totalAttribue = 0;
        foreach ($prototypes as $p) {
            $note = $p->note ?? 0;
            $remplissage = $note / $p->bareme; // Exemple : 3 / 5 = 0.6
            $ratio = $remplissage / $totalRemplissage; // Exemple : 0.6 / 1.1 ≈ 0.5455
            $noteProposee = $roundToStep($noteTotale * $ratio); // Ex: 5 * 0.5455 ≈ 2.75
            $noteAppliquee = min($noteProposee, $p->bareme);
            $noteAppliquee = $roundToStep($noteAppliquee);

            $repartitions[] = [
                'proto' => $p,
                'note_appliquee' => $noteAppliquee,
                'reste_possible' => max($p->bareme - $noteAppliquee, 0),
            ];

            $totalAttribue += $noteAppliquee;
        }

        // 2️⃣ Correction finale : forcer la somme exacte = note de la tâche
        $ecart = round($noteTotale - $totalAttribue, 2); // positif ou négatif
        $step = 0.25;
        if (abs($ecart) >= 0.01) {
                $maxIterations = 1000;
                $i = 0;

                while (abs($ecart) >= 0.01 && $i < $maxIterations) {
                    // Trier les prototypes par reste possible (ajout) ou note actuelle (retrait)
                    usort($repartitions, function ($a, $b) use ($ecart) {
                        return $ecart > 0
                            ? $b['reste_possible'] <=> $a['reste_possible']
                            : $b['note_appliquee'] <=> $a['note_appliquee'];
                    });

                    $modification = false;

                    foreach ($repartitions as &$entry) {
                        $proto = $entry['proto'];
                        $note = $entry['note_appliquee'];

                        if ($ecart > 0 && $note + $step <= $proto->bareme) {
                            $entry['note_appliquee'] += $step;
                            $ecart = round($ecart - $step, 2);
                            $modification = true;
                            break;
                        }

                        if ($ecart < 0 && $note - $step >= 0) {
                            $entry['note_appliquee'] -= $step;
                            $ecart = round($ecart + $step, 2);
                            $modification = true;
                            break;
                        }
                    }

                    unset($entry); // Sécurité

                    if (!$modification) break;
                    $i++;
                }

                // ✅ Si l'écart résiduel est exactement ±0.25 → appliquer une dernière correction
                if (abs($ecart) === 0.25) {
                    foreach ($repartitions as &$entry) {
                        $proto = $entry['proto'];
                        $note = $entry['note_appliquee'];

                        if ($ecart > 0 && $note + 0.25 <= $proto->bareme) {
                            $entry['note_appliquee'] += 0.25;
                            break;
                        }

                        if ($ecart < 0 && $note - 0.25 >= 0) {
                            $entry['note_appliquee'] -= 0.25;
                            break;
                        }
                    }
                    unset($entry);
                }
            }

        // 3️⃣ Application finale (arrondi garanti à 0.25)
        foreach ($repartitions as $entry) {
            $entry['proto']->note = $entry['note_appliquee'];
            $entry['proto']->save();
        }
    }


}