<?php


namespace Modules\PkgApprentissage\Services;

use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaPrototypeService;

/**
 * Classe RealisationUaPrototypeService pour gérer la persistance de l'entité RealisationUaPrototype.
 */
class RealisationUaPrototypeService extends BaseRealisationUaPrototypeService
{

    public function initFieldsFilterable()
    {

        $scopeVariables = $this->viewState->getScopeVariables('realisationUaPrototype');
        $this->fieldsFilterable = [];
        $sessionState = $this->sessionState;

        // Groupe
        if (Auth::user()->hasRole(Role::ADMIN_ROLE) || !Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE, Role::APPRENANT_ROLE) || !empty($this->viewState->get("filter.realisationUaPrototype.RealisationTache.RealisationProjet.AffectationProjet.Groupe_id"))) {
            $groupeService = new GroupeService();
            $groupes = $groupeService->all();
            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgApprenants::Groupe.plural"),
                'RealisationTache.RealisationProjet.AffectationProjet.Groupe_id',
                Groupe::class,
                "code",
                "id",
                $groupes,
                "[name='RealisationTache.RealisationProjet.Affectation_projet_id']",
                route('affectationProjets.getData'),
                "groupe_id"
            );
        }

        // AffectationProjet
        $affectationProjetService = new AffectationProjetService();
        $affectationProjets = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => $affectationProjetService->getAffectationProjetsByFormateurId($sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => $affectationProjetService->getAffectationProjetsByApprenantId($sessionState->get("apprenant_id")),
            default => AffectationProjet::all(),
        };
        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgRealisationProjets::affectationProjet.plural"),
            'RealisationTache.RealisationProjet.Affectation_projet_id',
            AffectationProjet::class,
            "id",
            "id",
            $affectationProjets,
            "[name='RealisationUa.Unite_apprentissage_id']",
            route('uniteApprentissages.getData'),
            "mobilisationUas.projet.affectationProjets.id"
        );

        // Unite Apprentissage s'adaptant à l'affectation
        $affectationProjetId = $this->viewState->get(
            'filter.realisationUaPrototype.RealisationTache.RealisationProjet.Affectation_projet_id'
        );

        $affectationProjetId = AffectationProjet::find($affectationProjetId) ? $affectationProjetId : null;

         if (!empty($affectationProjetId)) {
            $affectationProjet = clone $affectationProjets->firstWhere('id', $affectationProjetId);
            if (!$affectationProjet) {
                $affectationProjet = (new AffectationProjetService())->find($affectationProjetId);
            }
            if ($affectationProjet && $affectationProjet->projet) {
                $uniteApprentissages = $affectationProjet->projet->mobilisationUas->map(function($mobilisation) {
                    return $mobilisation->uniteApprentissage;
                })->filter()->unique('id');
            } else {
                 $uniteApprentissages = collect();
            }
        } else {
             $uniteApprentissages = UniteApprentissage::all();
        }

        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgCompetences::uniteApprentissage.plural"),
            'RealisationUa.Unite_apprentissage_id',
            UniteApprentissage::class,
            "nom",
            "id",
            $uniteApprentissages
        );

        // Apprenant
        $apprenants = match (true) {
            Auth::user()->hasRole(Role::FORMATEUR_ROLE) => (new FormateurService())->getApprenants($this->sessionState->get("formateur_id")),
            Auth::user()->hasRole(Role::APPRENANT_ROLE) => (new ApprenantService())->getApprenantsDeGroupe($this->sessionState->get("apprenant_id")),
            default => Apprenant::all(),
        };
        $this->fieldsFilterable[] = $this->generateRelationFilter(
            __("PkgApprenants::apprenant.plural"),
            'RealisationUa.RealisationMicroCompetence.Apprenant_id',
            Apprenant::class,
            "id",
            "id",
            $apprenants
        );
    }

    public function defaultSort($query)
    {
        return $query
            ->join('realisation_uas', 'realisation_ua_prototypes.realisation_ua_id', '=', 'realisation_uas.id')
            ->join('unite_apprentissages', 'realisation_uas.unite_apprentissage_id', '=', 'unite_apprentissages.id')
            ->orderBy('unite_apprentissages.ordre', 'asc')
            ->select('realisation_ua_prototypes.*');
    }

    /**
     * Job déclenché après mise à jour d'un RealisationUaPrototype.
     *
     * @param int    $id
     * @param string $token
     * @return void
     */
    public function updatedObserverJob(int $id, string $token): void
    {
        $jobManager = new JobManager($token);
        $changedFields = $jobManager->getChangedFields();

        /** @var RealisationUaPrototype|null $realisationUaPrototype */
        $realisationUaPrototype = RealisationUaPrototype::find($id);
        if (! $realisationUaPrototype) {
            return;
        }

        // 🔹 Si la note ou le barème a changé
        if (
            $jobManager->isDirty('note') ||
            $jobManager->isDirty('bareme')
        ) {
            if ($realisationUaPrototype->realisation_tache_id) {
                $realisationTache = $realisationUaPrototype->realisationTache;

                if ($realisationTache) {
                    // Récupérer tous les prototypes liés à cette tâche
                    $prototypes = RealisationUaPrototype::where('realisation_tache_id', $realisationTache->id)->get();

                    // Calcul de la note totale (max = barème)
                    $noteTotale = $prototypes->sum(function ($proto) {
                        return min($proto->note ?? 0, $proto->bareme ?? 0);
                    });

                    // Label du job
                    $jobManager->setLabel("Mise à jour de la note de la tâche #{$realisationTache->id}");

                    // ⚡ Mise à jour pour déclencher l’updatedObserverJob
                    $realisationTache->update([
                        'note' => round($noteTotale, 2)
                    ]);
                }
            }
        }
    }

    /**
     * Hook appelé après la mise à jour d'un RealisationUaPrototype
     * Permet de mettre à jour le barème et la note de RealisationUaProjet associé.
     *
     * @param RealisationUaPrototype $item
     * @return void
     */
    public function afterUpdateRules($item): void
    {
        /** @var RealisationUaPrototype $realisationUaPrototype */
        $realisationUaPrototype = $item;

        // Si la note est présente, on exécute le calcul
        if ($realisationUaPrototype->note !== null) {
            $realisationUa = $realisationUaPrototype->realisationUa;
            
            // L'attribut dynamique n'est pas forcément chargé ici, on passe par la relation
            $realisationTache = $realisationUaPrototype->realisationTache;
            $realisationProjetId = $realisationTache ? $realisationTache->realisation_projet_id : null;

            if ($realisationUa && $realisationProjetId) {
                // Chercher le RealisationUaProjet qui correspond au même apprenant et à la même UA
                $realisationUaProjet = \Modules\PkgApprentissage\Models\RealisationUaProjet::where('realisation_ua_id', $realisationUa->id)
                    ->whereHas('realisationTache', function ($query) use ($realisationProjetId) {
                        $query->where('realisation_projet_id', $realisationProjetId);
                    })->first();

                if ($realisationUaProjet) {
                    // Vérifier si la tâche du PROJET FINAL est dans un état final
                    $etatService = new \Modules\PkgRealisationTache\Services\EtatRealisationTacheService();
                    $tacheProjet = $realisationUaProjet->realisationTache;
                    $isFinal = $tacheProjet && $tacheProjet->etatRealisationTache 
                        ? $etatService->isEtatFinal($tacheProjet->etatRealisationTache) 
                        : false;

                    if (!$isFinal) {
                        $realisationProjet = \Modules\PkgRealisationProjets\Models\RealisationProjet::with('affectationProjet.projet')
                            ->find($realisationProjetId);
                        
                        if ($realisationProjet && $realisationProjet->affectationProjet && $realisationProjet->affectationProjet->projet) {
                            $projet = $realisationProjet->affectationProjet->projet;

                            if ($projet->is_auto_calcule_note_realisation) {
                                $projetId = $projet->id;
                                $uniteApprentissageId = $realisationUa->unite_apprentissage_id;

                                $mobilisationUa = \Modules\PkgCreationProjet\Models\MobilisationUa::where('projet_id', $projetId)
                                    ->where('unite_apprentissage_id', $uniteApprentissageId)
                                    ->first();

                                if ($mobilisationUa) {
                                    $baremeProjet = $mobilisationUa->bareme_evaluation_projet ?? 2;
                                    $baremePrototype = $mobilisationUa->bareme_evaluation_prototype ?? 4;

                                    // Calculer la note RealisationUaProjet à partir de celle du Prototype
                                    if ($baremePrototype > 0 && $realisationUaPrototype->note !== null) {
                                        $noteProjet = ($realisationUaPrototype->note / $baremePrototype) * $baremeProjet;
                                        
                                        // Mise à jour de la note du projet car la tâche n'est pas encore finalisée
                                        $realisationUaProjet->update([
                                            'note' => round($noteProjet, 2),
                                            'bareme' => $baremeProjet
                                        ]);

                                      
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


}
