<?php


namespace Modules\PkgWidgets\Controllers;
use Illuminate\Http\Request;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgWidgets\Controllers\Base\BaseWidgetUtilisateurController;

class WidgetUtilisateurController extends BaseWidgetUtilisateurController
{

    /**
     * @DynamicPermissionIgnore
     * Met à jour les attributs d’un widget utilisateur (ordre et/ou visibilité).
     */
    public function updateAttributes(Request $request)
    {
        if (!auth()->user()->can('update-widgetUtilisateur')) {
            abort(403, 'Permission refusée : update-widgetUtilisateur');
        }

        $validated = $request->validate([
            'id' => 'required|integer|exists:widget_utilisateurs,id',
            'ordre' => 'nullable|integer|min:1',
            'visible' => 'nullable|boolean',
        ]);

        $dataToUpdate = [];

        if (array_key_exists('ordre', $validated)) {
            $dataToUpdate['ordre'] = $validated['ordre'];
        }

        if (array_key_exists('visible', $validated)) {
            $dataToUpdate['visible'] = $validated['visible'];
        }

        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.', 422);
        }

        $this->widgetUtilisateurService->update($validated['id'], $dataToUpdate);

        return JsonResponseHelper::success(
            __('Mise à jour réussie.'),
            ['entity_id' => $validated['id']]
        );
    }

    
    


}
