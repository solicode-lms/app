<?php
 

namespace Modules\PkgRealisationTache\Controllers;


use Modules\PkgRealisationTache\Controllers\Base\BaseRealisationTacheController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;

class RealisationTacheController extends BaseRealisationTacheController
{
    public function index(Request $request){

        $this->viewState->setContextKeyIfEmpty('realisationTache.index');
        
        if(Auth::user()->hasRole('evaluateur') && !Auth::user()->hasRole('formateur')){
            $this->viewState->init('realisationTache_view_type', "table-evaluation");
        }
        return parent::index($request);
    }


    /**
     * Retourne les métadonnées d’un champ (type, options, validation, etag…)
     *  @DynamicPermissionIgnore
     */
    public function fieldMeta(int $id, string $field)
    {
        // $this->authorizeAction('update');
        $itemRealisationTache = RealisationTache::findOrFail($id);

        // scopeDataInEditContext
        $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
        $key = 'scope.etatRealisationTache.formateur_id';
        $this->viewState->set($key, $value);

        $data = $this->service->buildFieldMeta($itemRealisationTache, $field);
        return response()->json(
            $data
        );
    }

    /**
     * PATCH inline d’une cellule avec gestion de l’ETag
     * @DynamicPermissionIgnore
     */
    public function patchInline(Request $request, int $id)
    {

        $this->authorizeAction('update');
        $itemRealisationTache = RealisationTache::findOrFail($id);

        // scopeDataInEditContext
        $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
        $key = 'scope.etatRealisationTache.formateur_id';
        $this->viewState->set($key, $value);
 
        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemRealisationTache);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }
        
        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemRealisationTache, $changes);

        return response()->json(
        array_merge(
                    [
                        "ok"        => true,
                        "entity_id" => $updated->id,
                        "display"   => $this->service->formatDisplayValues($updated, array_keys($changes)),
                        "etag"      => $this->service->etag($updated),
                    ],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
        );
    }

    
}
