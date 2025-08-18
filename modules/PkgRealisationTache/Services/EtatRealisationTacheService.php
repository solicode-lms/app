<?php


namespace Modules\PkgRealisationTache\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\PkgRealisationTache\Services\Base\BaseEtatRealisationTacheService;

/**
 * Classe EtatRealisationTacheService pour gérer la persistance de l'entité EtatRealisationTache.
 */
class EtatRealisationTacheService extends BaseEtatRealisationTacheService
{

    protected array $index_with_relations = ['sysColor','workflowTache','formateur'];

    // ✅ Définir ici la whitelist des états autorisés pour un apprenant
    protected array $workflowTacheAutorisesApprenant = [
        'TODO',
        'IN_PROGRESS',
        'PAUSED',
        'IN_LIVE_CODING',
        'TO_APPROVE',
    ];
   
    /**
     * Override de all() avec filtrage selon le rôle
     */
    public function getAllForSelect($etatRealisationTache): Collection
    {

        if($etatRealisationTache){
             $this->workflowTacheAutorisesApprenant[] = $etatRealisationTache?->workflowTache?->code;
        }
       
        return $this->model->withScope(function ()  {

            $query = $this->newQuery();
            // Si rôle Apprenant → appliquer le filtre sur WorkflowTache
            if (Auth::check() && Auth::user()->hasRole(Role::APPRENANT_ROLE)) {
                $query->whereHas('workflowTache', function ($q) {
                    $q->whereIn('code', $this->workflowTacheAutorisesApprenant);
                });
            }
            $query->orderBy('ordre');
            return $query->get();
        });
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
     * Récupère l'état par défaut  TODO
     * S'il n'existe pas, les états sont créés à partir des workflows.
     *
     * @param int $formateurId
     * @return EtatRealisationTache|null
     */
    public function getDefaultEtatByFormateurId(int $formateurId)
    {
        // 1. On cherche directement l'état "TODO"
        $etatTodo = $this->model
            ->where('formateur_id', $formateurId)
            ->whereHas('workflowTache', function ($q) {
                $q->where('code', 'TODO');
            })
            ->first();

        // 2. Si non trouvé, on crée les états par défaut pour ce formateur
        if (!$etatTodo) {
            $this->createDefaultEtatsFromWorkflow($formateurId);

            // 3. On relance la recherche du TODO
            $etatTodo = $this->model
                ->where('formateur_id', $formateurId)
                ->whereHas('workflowTache', function ($q) {
                    $q->where('code', 'TODO');
                })
                ->first();
        }

        return $etatTodo;
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
                    'is_editable_only_by_formateur' => $workflow->is_editable_only_by_formateur
                ]);
            }
        });
    }


   /**
     * Récupère un état par formateur et code workflow.
     * Crée les états du formateur si aucun n'existe.
     * Ne crée pas le WorkflowTache : il doit exister.
     *
     * @param int $formateurId
     * @param string $workflowCode
     * @return \Modules\PkgRealisationTache\Models\EtatRealisationTache|null
     */
    public function findByFormateurIdAndWorkflowCode(int $formateurId, string $workflowCode)
    {
        // 1. Trouver le workflow existant
        $workflow = WorkflowTache::where('code', $workflowCode)->first();

        if (!$workflow) {
            return null; // ou throw new \Exception("Workflow introuvable")
        }

        // 2. Vérifier si le formateur a déjà des états
        $etatCount = $this->model->where('formateur_id', $formateurId)->count();

        if ($etatCount === 0) {
            $this->createDefaultEtatsFromWorkflow($formateurId);
        }

        // 3. Renvoyer l’état associé à ce workflow
        return $this->model
            ->where('formateur_id', $formateurId)
            ->where('workflow_tache_id', $workflow->id)
            ->first();
    }

    
   
}
