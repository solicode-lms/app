<?php
 

namespace Modules\PkgGestionTaches\Controllers;


use Modules\PkgGestionTaches\Controllers\Base\BaseRealisationTacheController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\Models\RealisationTache;

class RealisationTacheController extends BaseRealisationTacheController
{
    /**
     *  @DynamicPermissionIgnore
     * Supprimer plusieurs tâches sélectionnées.
     */
    public function bulkDelete(Request $request)
    {
        $this->authorizeAction('destroy');

        $ids = $request->input('ids', []);

        if (!is_array($ids) || count($ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }

        foreach ($ids as $id) {
            $entity = $this->realisationTacheService->find($id);
            $this->authorize('delete', $entity);
            $this->realisationTacheService->destroy($id);
        }

        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($ids) . ' éléments',
            'modelName' => __('PkgGestionTaches::realisationTache.plural')
        ]));
    }

    /**
     * @DynamicPermissionIgnore
     * Affiche le formulaire d'édition en masse.
     */
    public function bulkEditForm(Request $request)
    {
        $this->authorizeAction('update');

        $ids = $request->input('ids', []);

        if (!is_array($ids) || count($ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

         // ownedByUser
         if(Auth::user()->hasRole('formateur')){
            $this->viewState->set('scope_form.realisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
         }
         if(Auth::user()->hasRole('apprenant')){
            $this->viewState->set('scope_form.realisationTache.RealisationProjet.Apprenant_id'  , $this->sessionState->get('apprenant_id'));
         }
 
 
         $itemRealisationTache = $this->realisationTacheService->createInstance();
         
         // scopeDataInEditContext
         $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
         $key = 'scope.etatRealisationTache.formateur_id';
         $this->viewState->set($key, $value);
 
         $taches = $this->tacheService->all();
         $realisationProjets = $this->realisationProjetService->all();
         $etatRealisationTaches = $this->etatRealisationTacheService->all();
 
         if (request()->ajax()) {
             return view('PkgGestionTaches::realisationTache._fields', compact('itemRealisationTache', 'etatRealisationTaches', 'realisationProjets', 'taches'));
         }
        
        // return view('PkgGestionTaches::realisationTache.create', compact('itemRealisationTache', 'etatRealisationTaches', 'realisationProjets', 'taches'));
 
        return view('PkgGestionTaches::realisationTache._edit', compact('ids', 'etatRealisationTaches'))->render();
    }

    /**
     * @DynamicPermissionIgnore
     * Enregistre les modifications pour plusieurs tâches.
     */
    public function bulkUpdate(Request $request)
    {
        $this->authorizeAction('update');
        
        $ids = $request->input('ids', []);
        $etat_id = $request->input('etat_realisation_tache_id');

        if (!is_array($ids) || count($ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }

        foreach ($ids as $id) {
            $entity = $this->realisationTacheService->find($id);
            $this->authorize('update', $entity);

            $this->realisationTacheService->update($id, [
                'etat_realisation_tache_id' => $etat_id
            ]);
        }

        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));
    }
}
