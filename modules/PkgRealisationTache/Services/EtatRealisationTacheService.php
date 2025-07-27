<?php


namespace Modules\PkgRealisationTache\Services;

use Illuminate\Support\Facades\DB;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgRealisationTache\Services\Base\BaseEtatRealisationTacheService;

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
     * S'il n'existe pas, les états sont créés à partir des workflows.
     *
     * @param int $formateurId
     * @return EtatRealisationTache|null
     */
    public function getDefaultEtatByFormateurId(int $formateurId)
    {
        $workflowTacheMin = WorkflowTache::orderBy('ordre', 'asc')->first();
        if (!$workflowTacheMin) {
            return null;
        }

        $defaultEtat = $this->model
            ->where('formateur_id', $formateurId)
            ->where('workflow_tache_id', $workflowTacheMin->id)
            ->first();

        if (!$defaultEtat) {
            $this->createDefaultEtatsFromWorkflow($formateurId);
            $defaultEtat = $this->model
                ->where('formateur_id', $formateurId)
                ->where('workflow_tache_id', $workflowTacheMin->id)
                ->first();
        }

        return $defaultEtat;
    }

       /**
     * Crée les états de réalisation par défaut à partir des workflows pour un formateur donné.
     *
     * @param int $formateurId
     * @return void
     */
    protected function createDefaultEtatsFromWorkflow(int $formateurId): void
    {
        DB::transaction(function () use ($formateurId) {
            $workflows = WorkflowTache::orderBy('ordre', 'asc')->get();

            foreach ($workflows as $workflow) {
                $this->create([
                    'nom' => $workflow->titre,
                    'ordre' => $workflow->ordre,
                    'description' => $workflow->description,
                    'formateur_id' => $formateurId,
                    'workflow_tache_id' => $workflow->id,
                    'sys_color_id' => $workflow?->sys_color_id ,
                ]);
            }
        });
    }
    
   
}
