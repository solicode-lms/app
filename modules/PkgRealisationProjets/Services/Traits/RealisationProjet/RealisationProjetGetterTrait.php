<?php

namespace Modules\PkgRealisationProjets\Services\Traits\RealisationProjet;

use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait RealisationProjetGetterTrait
{
    public function initFieldsFilterable()
    {

        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationProjet');
        $this->fieldsFilterable = [];

        // Groupe 
        if (!Auth::user()->hasAnyRole(Role::FORMATEUR_ROLE, Role::APPRENANT_ROLE) || !empty($this->viewState->get("filter.realisationProjet.AffectationProjet.Groupe_id"))) {
            // Affichage de l'état de solicode
            $groupeService = new GroupeService();
            $groupes = $groupeService->all();
            $this->fieldsFilterable[] = $this->generateRelationFilter(
                __("PkgApprenants::Groupe.plural"),
                'AffectationProjet.Groupe_id',
                Groupe::class,
                "code",
                "id",
                $groupes,
                "[name='affectation_projet_id']",
                route('affectationProjets.getData'),
                "groupe_id"
            );
        }

        // AffectationProjet
        if (Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
            $affectationProjets = (new AffectationProjetService())->getAffectationProjetsByFormateurId($this->sessionState->get("formateur_id"));
        } elseif (Auth::user()->hasRole(Role::APPRENANT_ROLE)) {
            $affectationProjets = (new AffectationProjetService())->getAffectationProjetsByApprenantId($this->sessionState->get("apprenant_id"));
        } else {
            $affectationProjets = AffectationProjet::all();
        }
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __("PkgRealisationProjets::affectationProjet.plural"),
            'affectation_projet_id',
            \Modules\PkgRealisationProjets\Models\AffectationProjet::class,
            'id',
            $affectationProjets
        );

        // Apprenant
        if (Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
            $apprenants = (new FormateurService())->getApprenants($this->sessionState->get("formateur_id"));
        } elseif (Auth::user()->hasRole(Role::APPRENANT_ROLE)) {
            $apprenants = (new ApprenantService())->getApprenantsDeGroupe($this->sessionState->get("apprenant_id"));
        } else {
            $apprenants = Apprenant::all();
        }
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __("PkgApprenants::apprenant.plural"),
            'apprenant_id',
            \Modules\PkgApprenants\Models\Apprenant::class,
            'nom',
            $apprenants
        );


        // If formateur ou apprenant
        $etatsRealisationProjetService = new \Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService();
        $etatsRealisationProjetIds = $this->getAvailableFilterValues('etats_realisation_projet_id');
        $etatsRealisationProjets = $etatsRealisationProjetService->getByIds($etatsRealisationProjetIds);

        $this->fieldsFilterable[] = $this->generateManyToOneFilter(
            __("PkgRealisationProjets::etatsRealisationProjet.plural"),
            'etats_realisation_projet_id',
            \Modules\PkgRealisationProjets\Models\EtatsRealisationProjet::class,
            'code',
            $etatsRealisationProjets
        );
    }

    public function paginate(array $params = [], int $perPage = 0, array $columns = ['*']): LengthAwarePaginator
    {
        $perPage = $perPage ?: $this->paginationLimit;

        return $this->model::withScope(function () use ($params, $perPage, $columns) {
            $query = $this->allQuery($params);

            // Vérification et application du filtre par formateur si disponible
            if (isset($params['formateur_id']) && !empty($params['formateur_id'])) {
                $formateur_id = $params['formateur_id'];

                $query->whereHas('affectationProjet', function ($query) use ($formateur_id) {
                    $query->whereHas('projet', function ($q) use ($formateur_id) {
                        $q->where('formateur_id', $formateur_id);
                    });
                });
            }

            // Filtrer par groupe des apprenants du même groupe
            if (!empty($params['scope_groupe_apprenant_id'])) {
                $apprenant_id = $params['scope_groupe_apprenant_id'];

                $query->whereHas('apprenant', function ($q) use ($apprenant_id) {
                    $q->whereHas('groupes', function ($g) use ($apprenant_id) {
                        $g->whereHas('apprenants', function ($a) use ($apprenant_id) {
                            $a->where('apprenants.id', $apprenant_id);
                        });
                    });
                });
            }


            $query->with(array_unique($this->index_with_relations));

            // Calcul du nombre total des résultats filtrés
            $this->totalFilteredCount = $query->count();

            return $query->paginate($perPage, $columns);
        });
    }
}
