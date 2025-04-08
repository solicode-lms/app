<?php
 

namespace Modules\PkgGestionTaches\Controllers;


use Modules\PkgGestionTaches\Controllers\Base\BaseRealisationTacheController;

class RealisationTacheController extends BaseRealisationTacheController
{
    // public function edit(string $id) {

    //     $this->viewState->setContextKey('realisationTache.edit_' . $id);


    //     $itemRealisationTache = $this->realisationTacheService->find($id);
    //     $this->authorize('edit', $itemRealisationTache);

    //     // scopeDataInEditContext
    //     $value = $itemRealisationTache->getNestedValue('tache.projet.formateur_id');
    //     $key = 'scope.etatRealisationTache.formateur_id';
    //     $this->viewState->set($key, $value);

    //     $taches = $this->tacheService->all();
    //     $realisationProjets = $this->realisationProjetService->all();
    //     $etatRealisationTaches = $this->etatRealisationTacheService->all();


    //     $this->viewState->set('scope.historiqueRealisationTache.realisation_tache_id', $id);
        

    //     $historiqueRealisationTacheService =  new HistoriqueRealisationTacheService();
    //     $historiqueRealisationTaches_view_data = $historiqueRealisationTacheService->prepareDataForIndexView();
    //     extract($historiqueRealisationTaches_view_data);

    //     if (request()->ajax()) {
    //         return view('PkgGestionTaches::realisationTache._edit', array_merge(compact('itemRealisationTache','etatRealisationTaches', 'realisationProjets', 'taches'),$historiqueRealisationTache_compact_value));
    //     }

    //     return view('PkgGestionTaches::realisationTache.edit', array_merge(compact('itemRealisationTache','etatRealisationTaches', 'realisationProjets', 'taches'),$historiqueRealisationTache_compact_value));

    // }

}
