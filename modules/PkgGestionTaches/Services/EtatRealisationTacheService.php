<?php


namespace Modules\PkgGestionTaches\Services;

use Modules\PkgGestionTaches\Models\WorkflowTache;
use Modules\PkgGestionTaches\Services\Base\BaseEtatRealisationTacheService;

/**
 * Classe EtatRealisationTacheService pour gérer la persistance de l'entité EtatRealisationTache.
 */
class EtatRealisationTacheService extends BaseEtatRealisationTacheService
{

    protected array $index_with_relations = ['sysColor','workflowTache','formateur'];
    
    public function dataCalcul($etatRealisationTache)
    {
        // En Cas d'édit
        if(isset($etatRealisationTache->id)){
          
        }
      
        return $etatRealisationTache;
    }

    /**
     * Récupérer les états de réalisation des tâches associés à un formateur donné.
     *
     * @param int $formateurId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEtatRealisationTacheByFormateurId(int $formateurId)
    {
        return $this->model->where('formateur_id', $formateurId)->get();
    }


    /**
     * Récupérer les états de réalisation des tâches des formateurs encadrant un apprenant donné.
     *
     * @param int $apprenantId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEtatRealisationTacheByFormateurDApprenantId(int $apprenantId)
    {
        return $this->model->whereHas('formateur', function ($query) use ($apprenantId) {
            $query->whereHas('groupes', function ($q) use ($apprenantId) {
                $q->whereHas('apprenants', function ($a) use ($apprenantId) {
                    $a->where('apprenants.id', $apprenantId);
                });
            });
        })->get();
    }

    /**
     * Récupère l'état par défaut (ordre minimal) défini par un formateur.
     *
     * @param int $formateurId
     * @return \Modules\PkgGestionTaches\Models\EtatRealisationTache|null
     */
    public function getDefaultEtatByFormateurId(int $formateurId)
    {
        // Récupérer le workflow ayant l'ordre le plus bas
        $workflowTacheMin = WorkflowTache::orderBy('ordre', 'asc')->first();
    
        if (!$workflowTacheMin) {
            return null;
        }
    
        return $this->model
            ->where('formateur_id', $formateurId)
            ->where('workflow_tache_id', $workflowTacheMin->id)
            ->first();
    }
    
   
}
