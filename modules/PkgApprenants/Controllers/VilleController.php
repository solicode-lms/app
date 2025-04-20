<?php
 

namespace Modules\PkgApprenants\Controllers;

use Illuminate\Http\Request;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprenants\App\Requests\VilleRequest;
use Modules\PkgApprenants\Controllers\Base\BaseVilleController;

class VilleController extends BaseVilleController
{
    
    // public function updateAttributes(Request $request)
    // {
    //     $this->authorizeAction('update');
    
    //     $updatableFields = $this->service->getFieldsEditable();
    
    //     // Instanciation de la classe de validation
    //     $villeRequest = new VilleRequest();
    
    //     // Récupération des règles complètes depuis VilleRequest
    //     $fullRules = $villeRequest->rules();
    
    //     // Filtrer uniquement les règles correspondant aux champs présents dans la requête ET modifiables
    //     $rules = collect($fullRules)
    //         ->only(array_intersect(array_keys($request->all()), $updatableFields))
    //         ->toArray();
    
    //     // Ajout obligatoire de l'ID
    //     $rules['id'] = ['required', 'integer', 'exists:villes,id'];
    
    //     // Validation dynamique
    //     $validated = $request->validate($rules);
    
    //     // Extraire les champs validés à mettre à jour
    //     $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
    //     if (empty($dataToUpdate)) {
    //         return JsonResponseHelper::error('Aucune donnée à mettre à jour.', 422);
    //     }
    
    //     $this->getService()->update($validated['id'], $dataToUpdate);
    
    //     return JsonResponseHelper::success(__('Mise à jour réussie.'), [
    //         'entity_id' => $validated['id']
    //     ]);
    // }
}
