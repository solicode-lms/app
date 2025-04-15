<?php
 

namespace Modules\PkgGestionTaches\Controllers;


use Modules\PkgGestionTaches\Controllers\Base\BaseRealisationTacheController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\Models\RealisationTache;

class RealisationTacheController extends BaseRealisationTacheController
{

    // /**
    //  * @DynamicPermissionIgnore
    //  * Affiche le formulaire d'édition en masse.
    //  */
    // public function bulkEditForm(Request $request)
    // {
    //     $this->authorizeAction('update');

    //     $realisationTache_ids = $request->input('ids', []);

    //     if (!is_array($realisationTache_ids) || count($realisationTache_ids) === 0) {
    //         return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
    //     }

    //     // Même traitement de create 

    //      // ownedByUser
    //      if(Auth::user()->hasRole('formateur')){
    //         $this->viewState->set('scope_form.realisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
    //      }
    //      if(Auth::user()->hasRole('apprenant')){
    //         $this->viewState->set('scope_form.realisationTache.RealisationProjet.Apprenant_id'  , $this->sessionState->get('apprenant_id'));
    //      }
 
    //      $itemRealisationTache = $this->realisationTacheService->find($realisationTache_ids[0]);
         
    //      // scopeDataInEditContext
    //      $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
    //      $key = 'scope.etatRealisationTache.formateur_id';
    //      $this->viewState->set($key, $value);
 
    //      $taches = $this->tacheService->all();
    //      $realisationProjets = $this->realisationProjetService->all();
    //      $etatRealisationTaches = $this->etatRealisationTacheService->all();
    //      $bulkEdit = true;

    //     //  Vider les valeurs : 
    //     $itemRealisationTache = $this->realisationTacheService->createInstance();
        
    //      if (request()->ajax()) {
    //          return view('PkgGestionTaches::realisationTache._fields', compact('bulkEdit','realisationTache_ids', 'itemRealisationTache', 'etatRealisationTaches', 'realisationProjets', 'taches'));
    //      }
        
    //     // return view('PkgGestionTaches::realisationTache.create', compact('itemRealisationTache', 'etatRealisationTaches', 'realisationProjets', 'taches'));
 
    //     return view('PkgGestionTaches::realisationTache._edit', compact('realisationTache_ids', 'etatRealisationTaches'))->render();
    // }

    // /**
    //  * @DynamicPermissionIgnore
    //  * Enregistre les modifications pour plusieurs tâches.
    //  */
    // public function bulkUpdate(Request $request)
    // {
    //     $this->authorizeAction('update');
    
    //     $realisationTache_ids = $request->input('realisationTache_ids', []);
    //     $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
    //     if (!is_array($realisationTache_ids) || count($realisationTache_ids) === 0) {
    //         return JsonResponseHelper::error("Aucun élément sélectionné.");
    //     }
    
    //     if (empty($champsCoches)) {
    //         return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
    //     }
    
    //     foreach ($realisationTache_ids as $id) {
    //         $entity = $this->realisationTacheService->find($id);
    //         $this->authorize('update', $entity);
    
           
    
    //         $allFields = $this->realisationTacheService->getFieldsEditable();
    //         $data = collect($allFields)
    //             ->filter(fn($field) => in_array($field, $champsCoches))
    //             ->mapWithKeys(fn($field) => [$field => $request->input($field)])
    //             ->toArray();
    
    //         if (!empty($data)) {
    //             $this->realisationTacheService->update($id, $data);
    //         }
    //     }
    
    //     return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));
    // }

    //  /**
    //  *  @DynamicPermissionIgnore
    //  *  Supprimer en masse.
    //  */
    // public function bulkDelete(Request $request)
    // {
    //     $this->authorizeAction('destroy');
    //     $realisationTache_ids = $request->input('ids', []);
    //     if (!is_array($realisationTache_ids) || count($realisationTache_ids) === 0) {
    //         return JsonResponseHelper::error("Aucun élément sélectionné.");
    //     }
    //     foreach ($realisationTache_ids as $id) {
    //         $entity = $this->realisationTacheService->find($id);
    //         $this->authorize('delete', $entity);
    //         $this->realisationTacheService->destroy($id);
    //     }
    //     return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
    //         'entityToString' => count($realisationTache_ids) . ' éléments',
    //         'modelName' => __('PkgGestionTaches::realisationTache.plural')
    //     ]));
    // }
}
