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

        // Bulk edit form traitement 

        $this->authorizeAction('update');

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.realisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.realisationTache.RealisationProjet.Apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }
 
        $itemRealisationTache = RealisationTache::findOrFail($id);

        // scopeDataInEditContext
        $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
        $key = 'scope.etatRealisationTache.formateur_id';
        $this->viewState->set($key, $value);

        return response()->json(
            $this->service->buildFieldMeta($itemRealisationTache, $field)
        );
    }

    /**
     * PATCH inline d’une cellule avec gestion de l’ETag
     * @DynamicPermissionIgnore
     */
    public function patchInline(Request $request, int $id)
    {



        // Bulk edit form traitement 

        $this->authorizeAction('update');

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.realisationTache.RealisationProjet.AffectationProjet.Projet.Formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.realisationTache.RealisationProjet.Apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }
 
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
