<?php

namespace Modules\PkgGestionTaches\Services;

use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgGestionTaches\Models\Tache;
use Modules\PkgGestionTaches\Services\Base\BaseRealisationTacheService;

/**
 * Classe RealisationTacheService pour gérer la persistance de l'entité RealisationTache.
 */
class RealisationTacheService extends BaseRealisationTacheService
{
    public function dataCalcul($realisationTache)
    {
        // En Cas d'édit
        if(isset($realisationTache->id)){
          
        }
      
        return $realisationTache;
    }


    public function initFieldsFilterable()
    {

        $scopeVariables = $this->viewState->getScopeVariables('realisationTache');
        $this->fieldsFilterable = [];
    
        // Filter : Tâche
        // Apprenant : ses tâches
        // Formateur : tous les tâches affecté
        // Autres : tous les tâches
        if(Auth::user()->hasRole(Role::FORMATEUR_ROLE)){
            $taches = (new TacheService())->getTacheByFormateurId($this->sessionState->get("formateur_id"));
        } elseif (Auth::user()->hasRole(Role::APPRENANT_ROLE)){
            $taches = (new TacheService())->getTacheByApprenantId($this->sessionState->get("apprenant_id"));
        } else{
            $taches = Tache::all();
        }
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::tache.plural"), 'tache_id', \Modules\PkgGestionTaches\Models\Tache::class, 'titre',$taches);
        
   
        if (!array_key_exists('etat_realisation_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::etatRealisationTache.plural"), 'etat_realisation_tache_id', \Modules\PkgGestionTaches\Models\EtatRealisationTache::class, 'nom');
        }

        // TODO : Gapp : il peut être ajotuer commme DataSource in Filter
        // Apprenant data
        if(Auth::user()->hasRole(Role::FORMATEUR_ROLE)){
            $apprenants = (new FormateurService())->getApprenants($this->sessionState->get("formateur_id"));
        } elseif (Auth::user()->hasRole(Role::APPRENANT_ROLE)){
            $apprenants = (new ApprenantService())->getApprenantsDeGroupe($this->sessionState->get("apprenant_id"));
        } else{
            $apprenants = Apprenant::all();
        }
        // TODO : Gapp add MetaData relationFilter
        $this->fieldsFilterable[] = $this->generateRelationFilter(__("PkgApprenants::apprenant.plural"), 'realisationProjet.apprenant_id', \Modules\PkgApprenants\Models\Apprenant::class,"id",$apprenants);

        $this->fieldsFilterable[] = $this->generateRelationFilter(__("PkgCreationProjet::projet.plural"), 'realisationProjet.affectationProjet.projet_id', \Modules\PkgCreationProjet\Models\Projet::class);


    }


    protected function generateManyToOneFilter(string $label, string $field, string $model, string $display_field,$data = null): array
    {
        $modelInstance = new $model();
       
        // Appliquer `withScope()` pour activer les scopes si disponibles
        $data = $data ?? $model::withScope(fn() => $model::all());

        return [
            'label' => $label,
            'field' => $field,
            'type' => 'ManyToOne',
            'options' => $data
                ->map(fn($item) => ['id' => $item['id'], 'label' => $item])
                ->toArray(),
            'sortable' => "{$modelInstance->getTable()}.{$display_field}",
        ];
    }
   
}
