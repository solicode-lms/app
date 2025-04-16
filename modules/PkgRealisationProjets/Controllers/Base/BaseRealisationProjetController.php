<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService;
use Modules\PkgRealisationProjets\Services\LivrablesRealisationService;
use Modules\PkgRealisationProjets\Services\ValidationService;
use Modules\PkgGestionTaches\Services\RealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationProjets\App\Requests\RealisationProjetRequest;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\RealisationProjetExport;
use Modules\PkgRealisationProjets\App\Imports\RealisationProjetImport;
use Modules\Core\Services\ContextState;

class BaseRealisationProjetController extends AdminController
{
    protected $realisationProjetService;
    protected $affectationProjetService;
    protected $apprenantService;
    protected $etatsRealisationProjetService;

    public function __construct(RealisationProjetService $realisationProjetService, AffectationProjetService $affectationProjetService, ApprenantService $apprenantService, EtatsRealisationProjetService $etatsRealisationProjetService) {
        parent::__construct();
        $this->service  =  $realisationProjetService;
        $this->realisationProjetService = $realisationProjetService;
        $this->affectationProjetService = $affectationProjetService;
        $this->apprenantService = $apprenantService;
        $this->etatsRealisationProjetService = $etatsRealisationProjetService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('realisationProjet.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('realisationProjet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.realisationProjet.affectationProjet.projet.formateur_id') == null){
           $this->viewState->init('filter.realisationProjet.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('filter.realisationProjet.apprenant_id') == null){
           $this->viewState->init('filter.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $realisationProjets_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'realisationProjets_search',
                $this->viewState->get("filter.realisationProjet.realisationProjets_search")
            )],
            $request->except(['realisationProjets_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationProjetService->prepareDataForIndexView($realisationProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgRealisationProjets::realisationProjet._index', $realisationProjet_compact_value)->render();
            }else{
                return view($realisationProjet_partialViewName, $realisationProjet_compact_value)->render();
            }
        }

        return view('PkgRealisationProjets::realisationProjet.index', $realisationProjet_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.realisationProjet.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }


        $itemRealisationProjet = $this->realisationProjetService->createInstance();
        
        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.formateur.id');
        $key = 'scope.etatsRealisationProjet.formateur_id';
        $this->viewState->set($key, $value);

        $affectationProjets = $this->affectationProjetService->all();
        $apprenants = $this->apprenantService->all();
        $etatsRealisationProjets = $this->etatsRealisationProjetService->all();

        if (request()->ajax()) {
            return view('PkgRealisationProjets::realisationProjet._fields', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets'));
        }
        return view('PkgRealisationProjets::realisationProjet.create', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $realisationProjet_ids = $request->input('ids', []);

        if (!is_array($realisationProjet_ids) || count($realisationProjet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.realisationProjet.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }
 
         $itemRealisationProjet = $this->realisationProjetService->find($realisationProjet_ids[0]);
         
        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.formateur.id');
        $key = 'scope.etatsRealisationProjet.formateur_id';
        $this->viewState->set($key, $value);
 
        $affectationProjets = $this->affectationProjetService->all();
        $apprenants = $this->apprenantService->all();
        $etatsRealisationProjets = $this->etatsRealisationProjetService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRealisationProjet = $this->realisationProjetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgRealisationProjets::realisationProjet._fields', compact('bulkEdit', 'realisationProjet_ids', 'itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets'));
        }
        return view('PkgRealisationProjets::realisationProjet.bulk-edit', compact('bulkEdit', 'realisationProjet_ids', 'itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets'));
    }
    /**
     */
    public function store(RealisationProjetRequest $request) {
        $validatedData = $request->validated();
        $realisationProjet = $this->realisationProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' => __('PkgRealisationProjets::realisationProjet.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $realisationProjet->id]
            );
        }

        return redirect()->route('realisationProjets.edit',['realisationProjet' => $realisationProjet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' => __('PkgRealisationProjets::realisationProjet.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('realisationProjet.edit_' . $id);


        $itemRealisationProjet = $this->realisationProjetService->edit($id);
        $this->authorize('view', $itemRealisationProjet);

        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.formateur.id');
        $key = 'scope.etatsRealisationProjet.formateur_id';
        $this->viewState->set($key, $value);

        $affectationProjets = $this->affectationProjetService->all();
        $apprenants = $this->apprenantService->all();
        $etatsRealisationProjets = $this->etatsRealisationProjetService->all();


        $this->viewState->set('scope.livrablesRealisation.realisation_projet_id', $id);
        
        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.id');
        $key = 'scope.livrable.projet_id';
        $this->viewState->set($key, $value);

        $livrablesRealisationService =  new LivrablesRealisationService();
        $livrablesRealisations_view_data = $livrablesRealisationService->prepareDataForIndexView();
        extract($livrablesRealisations_view_data);

        $this->viewState->set('scope.validation.realisation_projet_id', $id);
        
        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.id');
        $key = 'scope.transfertCompetence.projet_id';
        $this->viewState->set($key, $value);

        $validationService =  new ValidationService();
        $validations_view_data = $validationService->prepareDataForIndexView();
        extract($validations_view_data);

        $this->viewState->set('scope.realisationTache.realisation_projet_id', $id);
        

        $realisationTacheService =  new RealisationTacheService();
        $realisationTaches_view_data = $realisationTacheService->prepareDataForIndexView();
        extract($realisationTaches_view_data);

        if (request()->ajax()) {
            return view('PkgRealisationProjets::realisationProjet._edit', array_merge(compact('itemRealisationProjet','affectationProjets', 'apprenants', 'etatsRealisationProjets'),$livrablesRealisation_compact_value, $validation_compact_value, $realisationTache_compact_value));
        }

        return view('PkgRealisationProjets::realisationProjet.edit', array_merge(compact('itemRealisationProjet','affectationProjets', 'apprenants', 'etatsRealisationProjets'),$livrablesRealisation_compact_value, $validation_compact_value, $realisationTache_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationProjet.edit_' . $id);


        $itemRealisationProjet = $this->realisationProjetService->edit($id);
        $this->authorize('edit', $itemRealisationProjet);

        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.formateur.id');
        $key = 'scope.etatsRealisationProjet.formateur_id';
        $this->viewState->set($key, $value);

        $affectationProjets = $this->affectationProjetService->all();
        $apprenants = $this->apprenantService->all();
        $etatsRealisationProjets = $this->etatsRealisationProjetService->all();


        $this->viewState->set('scope.livrablesRealisation.realisation_projet_id', $id);
        
        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.id');
        $key = 'scope.livrable.projet_id';
        $this->viewState->set($key, $value);

        $livrablesRealisationService =  new LivrablesRealisationService();
        $livrablesRealisations_view_data = $livrablesRealisationService->prepareDataForIndexView();
        extract($livrablesRealisations_view_data);

        $this->viewState->set('scope.validation.realisation_projet_id', $id);
        
        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.id');
        $key = 'scope.transfertCompetence.projet_id';
        $this->viewState->set($key, $value);

        $validationService =  new ValidationService();
        $validations_view_data = $validationService->prepareDataForIndexView();
        extract($validations_view_data);

        $this->viewState->set('scope.realisationTache.realisation_projet_id', $id);
        

        $realisationTacheService =  new RealisationTacheService();
        $realisationTaches_view_data = $realisationTacheService->prepareDataForIndexView();
        extract($realisationTaches_view_data);

        if (request()->ajax()) {
            return view('PkgRealisationProjets::realisationProjet._edit', array_merge(compact('itemRealisationProjet','affectationProjets', 'apprenants', 'etatsRealisationProjets'),$livrablesRealisation_compact_value, $validation_compact_value, $realisationTache_compact_value));
        }

        return view('PkgRealisationProjets::realisationProjet.edit', array_merge(compact('itemRealisationProjet','affectationProjets', 'apprenants', 'etatsRealisationProjets'),$livrablesRealisation_compact_value, $validation_compact_value, $realisationTache_compact_value));


    }
    /**
     */
    public function update(RealisationProjetRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $realisationProjet = $this->realisationProjetService->find($id);
        $this->authorize('update', $realisationProjet);

        $validatedData = $request->validated();
        $realisationProjet = $this->realisationProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' =>  __('PkgRealisationProjets::realisationProjet.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $realisationProjet->id]
            );
        }

        return redirect()->route('realisationProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' =>  __('PkgRealisationProjets::realisationProjet.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $realisationProjet_ids = $request->input('realisationProjet_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($realisationProjet_ids) || count($realisationProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($realisationProjet_ids as $id) {
            $entity = $this->realisationProjetService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->realisationProjetService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->realisationProjetService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $realisationProjet = $this->realisationProjetService->find($id);
        $this->authorize('delete', $realisationProjet);

        $realisationProjet = $this->realisationProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' =>  __('PkgRealisationProjets::realisationProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('realisationProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' =>  __('PkgRealisationProjets::realisationProjet.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $realisationProjet_ids = $request->input('ids', []);
        if (!is_array($realisationProjet_ids) || count($realisationProjet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($realisationProjet_ids as $id) {
            $entity = $this->realisationProjetService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $realisationProjet = $this->realisationProjetService->find($id);
            $this->authorize('delete', $realisationProjet);
            $this->realisationProjetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($realisationProjet_ids) . ' éléments',
            'modelName' => __('PkgRealisationProjets::realisationProjet.plural')
        ]));
    }

    public function export($format)
    {
        $realisationProjets_data = $this->realisationProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationProjetExport($realisationProjets_data,'csv'), 'realisationProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationProjetExport($realisationProjets_data,'xlsx'), 'realisationProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else {
            return response()->json(['error' => 'Format non supporté'], 400);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new RealisationProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationProjets::realisationProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationProjets()
    {
        $realisationProjets = $this->realisationProjetService->all();
        return response()->json($realisationProjets);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $realisationProjet = $this->realisationProjetService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedRealisationProjet = $this->realisationProjetService->dataCalcul($realisationProjet);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedRealisationProjet
        ]);
    }
    

}