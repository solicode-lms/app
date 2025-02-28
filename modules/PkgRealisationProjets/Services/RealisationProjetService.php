<?php


namespace Modules\PkgRealisationProjets\Services;

use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationProjets\Services\Base\BaseRealisationProjetService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;
/**
 * 
 * Classe RealisationProjetService pour gérer la persistance de l'entité RealisationProjet.
 */
class RealisationProjetService extends BaseRealisationProjetService
{

    public function initFieldsFilterable(){


        // affectationProjet data
        if(Auth::user()->hasRole(Role::FORMATEUR_ROLE)){
            $affectationProjets = (new AffectationProjetService())->getAffectationProjetsByFormateur($this->sessionState->get("formateur_id"));
        } elseif (Auth::user()->hasRole(Role::APPRENANT_ROLE)){
            $affectationProjets = (new AffectationProjetService())->getAffectationProjetsByApprenant($this->sessionState->get("apprenant_id"));
        } else{
            $affectationProjets = AffectationProjet::all();
        }


        // Apprenant data
        if(Auth::user()->hasRole(Role::FORMATEUR_ROLE)){
            $apprenants = (new FormateurService())->getApprenants($this->sessionState->get("formateur_id"));
        } elseif (Auth::user()->hasRole(Role::APPRENANT_ROLE)){
            $apprenants = (new ApprenantService())->getApprenantsDeGroupe($this->sessionState->get("apprenant_id"));
        } else{
            $apprenants = Apprenant::all();
        }

        // etatsRealisationProjet
        if(Auth::user()->hasRole(Role::FORMATEUR_ROLE)){
            $etatsRealisationProjets = (new EtatsRealisationProjetService())->getByFormateur($this->sessionState->get("formateur_id"));
        } elseif (Auth::user()->hasRole(Role::APPRENANT_ROLE)){
            $etatsRealisationProjets = (new EtatsRealisationProjetService())->getEtatsByFormateurPrincipalForApprenant($this->sessionState->get("apprenant_id"));
        } else{
            $etatsRealisationProjets = EtatsRealisationProjet::all();
        }

     
        
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgRealisationProjets::affectationProjet.plural"), 'affectation_projet_id', \Modules\PkgRealisationProjets\Models\AffectationProjet::class, 'id',$affectationProjets),
            $this->generateManyToOneFilter(__("PkgApprenants::apprenant.plural"), 'apprenant_id', \Modules\PkgApprenants\Models\Apprenant::class, 'nom',$apprenants),
            $this->generateManyToOneFilter(__("PkgRealisationProjets::etatsRealisationProjet.plural"), 'etats_realisation_projet_id', \Modules\PkgRealisationProjets\Models\EtatsRealisationProjet::class, 'titre',$etatsRealisationProjets),
        ];

       
       
     }

    public function dataCalcul($realisationProjet)
    {
        // En Cas d'édit
        if(isset($realisationProjet->id)){
          
        }
      
        return $realisationProjet;
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

          
            $relationsToLoad = ["affectationProjet","apprenant","etatsRealisationProjet","livrablesRealisations","validations"];
            $query->with(array_unique($relationsToLoad));

            // Calcul du nombre total des résultats filtrés
            $this->totalFilteredCount = $query->count();

            return $query->paginate($perPage, $columns);
        });


       
    }
    
 

    /**
     * Summary of update
     * 
     */
    public function update($id, array $data)
    {
        $record =  $this->find($id);
        // Vérifier si l'état de réalisation du projet est défini
        if (!empty($data["etats_realisation_projet_id"])) {
            
            $etatsRealisationProjet = (new EtatsRealisationProjetService())->find($data["etats_realisation_projet_id"]);
    
            // Vérifier si l'état est éditable uniquement par le formateur
            if ($record->etatsRealisationProjet && $record->etatsRealisationProjet->is_editable_by_formateur && !Auth::user()->hasRole(Role::FORMATEUR_ROLE)) {
              
                throw ValidationException::withMessages([
                    'etats_realisation_projet_id' => "L'état de réalisation du projet spécifié est invalide."
                ]);


                return $record;
            }
        }
    
        // Mise à jour standard du projet
        return parent::update($id, $data);
    }
    

    
 
   
}
