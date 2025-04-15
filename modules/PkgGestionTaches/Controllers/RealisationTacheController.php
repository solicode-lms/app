<?php
 

namespace Modules\PkgGestionTaches\Controllers;


use Modules\PkgGestionTaches\Controllers\Base\BaseRealisationTacheController;
use Illuminate\Http\Request;
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

        $etatRealisationTaches = $this->etatRealisationTacheService->all();

        return view('PkgGestionTaches::realisationTache._bulk-edit', compact('ids', 'etatRealisationTaches'))->render();
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
