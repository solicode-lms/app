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
use Modules\PkgApprentissage\Models\RealisationUaProjet;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaProjetService;

/**
 * Classe RealisationUaProjetService pour gérer la persistance de l'entité RealisationUaProjet.
 */
class RealisationUaProjetService extends BaseRealisationUaProjetService
{
    public function initFieldsFilterable()
    {
        $scopeVariables = $this->viewState->getScopeVariables('realisationUaProjet');
        $this->fieldsFilterable = [];
        $sessionState = $this->sessionState;

        // Groupe
        if (Auth::user()->hasRole(Role::ADMIN_ROLE) || !Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE, Role::APPRENANT_ROLE) || !empty($this->viewState->get("filter.realisationUaProjet.RealisationTache.RealisationProjet.AffectationProjet.Groupe_id"))) {
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
            'filter.realisationUaProjet.RealisationTache.RealisationProjet.Affectation_projet_id'
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
            ->join('realisation_uas', 'realisation_ua_projets.realisation_ua_id', '=', 'realisation_uas.id')
            ->join('unite_apprentissages', 'realisation_uas.unite_apprentissage_id', '=', 'unite_apprentissages.id')
            ->orderBy('unite_apprentissages.ordre', 'asc')
            ->select('realisation_ua_projets.*');
    }
    
    public function updatedObserverJob(int $id, string $token): void
    {
        $jobManager = new JobManager($token);
        $changedFields = $jobManager->getChangedFields();

        $realisationUaProjet = $this->find($id);
        if (! $realisationUaProjet) {
            return;
        }

        // 2️⃣ Recalculer la note de la tâche à partir des RealisationUaProjets
        if ($realisationUaProjet->realisation_tache_id) {
            $tache = $realisationUaProjet->realisationTache;

            if ($tache) {
                $realisationUaProjets = RealisationUaProjet::where('realisation_tache_id', $tache->id)->get();

                $noteTotale = $realisationUaProjets->sum(function ($projet) {
                    return min($projet->note ?? 0, $projet->bareme ?? 0);
                });

                $jobManager->setLabel("Mise à jour de la note : #{$tache}");
                $tache->update([
                    'note' => round($noteTotale, 2)
                ]);
                $jobManager->tick();
            }
        }

        $jobManager->finish();
    }

 
}
