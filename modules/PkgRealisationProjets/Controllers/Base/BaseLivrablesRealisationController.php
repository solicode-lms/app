<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\LivrablesRealisationService;
use Modules\PkgCreationProjet\Services\LivrableService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationProjets\App\Requests\LivrablesRealisationRequest;
use Modules\PkgRealisationProjets\Models\LivrablesRealisation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\LivrablesRealisationExport;
use Modules\PkgRealisationProjets\App\Imports\LivrablesRealisationImport;
use Modules\Core\Services\ContextState;

class BaseLivrablesRealisationController extends AdminController
{
    protected $livrablesRealisationService;
    protected $livrableService;
    protected $realisationProjetService;

    public function __construct(LivrablesRealisationService $livrablesRealisationService, LivrableService $livrableService, RealisationProjetService $realisationProjetService) {
        parent::__construct();
        $this->service  =  $livrablesRealisationService;
        $this->livrablesRealisationService = $livrablesRealisationService;
        $this->livrableService = $livrableService;
        $this->realisationProjetService = $realisationProjetService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('livrablesRealisation.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('livrablesRealisation');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('filter.livrablesRealisation.realisationProjet.apprenant_id') == null){
           $this->viewState->init('filter.livrablesRealisation.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $livrablesRealisations_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'livrablesRealisations_search',
                $this->viewState->get("filter.livrablesRealisation.livrablesRealisations_search")
            )],
            $request->except(['livrablesRealisations_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->livrablesRealisationService->prepareDataForIndexView($livrablesRealisations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgRealisationProjets::livrablesRealisation._index', $livrablesRealisation_compact_value)->render();
            }else{
                return view($livrablesRealisation_partialViewName, $livrablesRealisation_compact_value)->render();
            }
        }

        return view('PkgRealisationProjets::livrablesRealisation.index', $livrablesRealisation_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.livrablesRealisation.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }


        $itemLivrablesRealisation = $this->livrablesRealisationService->createInstance();
        

        $livrables = $this->livrableService->all();
        $realisationProjets = $this->realisationProjetService->all();

        if (request()->ajax()) {
            return view('PkgRealisationProjets::livrablesRealisation._fields', compact('itemLivrablesRealisation', 'livrables', 'realisationProjets'));
        }
        return view('PkgRealisationProjets::livrablesRealisation.create', compact('itemLivrablesRealisation', 'livrables', 'realisationProjets'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $livrablesRealisation_ids = $request->input('ids', []);

        if (!is_array($livrablesRealisation_ids) || count($livrablesRealisation_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.livrablesRealisation.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }
 
         $itemLivrablesRealisation = $this->livrablesRealisationService->find($livrablesRealisation_ids[0]);
         
 
        $livrables = $this->livrableService->all();
        $realisationProjets = $this->realisationProjetService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemLivrablesRealisation = $this->livrablesRealisationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgRealisationProjets::livrablesRealisation._fields', compact('bulkEdit', 'livrablesRealisation_ids', 'itemLivrablesRealisation', 'livrables', 'realisationProjets'));
        }
        return view('PkgRealisationProjets::livrablesRealisation.bulk-edit', compact('bulkEdit', 'livrablesRealisation_ids', 'itemLivrablesRealisation', 'livrables', 'realisationProjets'));
    }
    /**
     */
    public function store(LivrablesRealisationRequest $request) {
        $validatedData = $request->validated();
        $livrablesRealisation = $this->livrablesRealisationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' => __('PkgRealisationProjets::livrablesRealisation.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $livrablesRealisation->id]
            );
        }

        return redirect()->route('livrablesRealisations.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' => __('PkgRealisationProjets::livrablesRealisation.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('livrablesRealisation.edit_' . $id);


        $itemLivrablesRealisation = $this->livrablesRealisationService->edit($id);
        $this->authorize('view', $itemLivrablesRealisation);


        $livrables = $this->livrableService->all();
        $realisationProjets = $this->realisationProjetService->all();


        if (request()->ajax()) {
            return view('PkgRealisationProjets::livrablesRealisation._fields', array_merge(compact('itemLivrablesRealisation','livrables', 'realisationProjets'),));
        }

        return view('PkgRealisationProjets::livrablesRealisation.edit', array_merge(compact('itemLivrablesRealisation','livrables', 'realisationProjets'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('livrablesRealisation.edit_' . $id);


        $itemLivrablesRealisation = $this->livrablesRealisationService->edit($id);
        $this->authorize('edit', $itemLivrablesRealisation);


        $livrables = $this->livrableService->all();
        $realisationProjets = $this->realisationProjetService->all();


        if (request()->ajax()) {
            return view('PkgRealisationProjets::livrablesRealisation._fields', array_merge(compact('itemLivrablesRealisation','livrables', 'realisationProjets'),));
        }

        return view('PkgRealisationProjets::livrablesRealisation.edit', array_merge(compact('itemLivrablesRealisation','livrables', 'realisationProjets'),));


    }
    /**
     */
    public function update(LivrablesRealisationRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $livrablesRealisation = $this->livrablesRealisationService->find($id);
        $this->authorize('update', $livrablesRealisation);

        $validatedData = $request->validated();
        $livrablesRealisation = $this->livrablesRealisationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' =>  __('PkgRealisationProjets::livrablesRealisation.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $livrablesRealisation->id]
            );
        }

        return redirect()->route('livrablesRealisations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' =>  __('PkgRealisationProjets::livrablesRealisation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $livrablesRealisation_ids = $request->input('livrablesRealisation_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($livrablesRealisation_ids) || count($livrablesRealisation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($livrablesRealisation_ids as $id) {
            $entity = $this->livrablesRealisationService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->livrablesRealisationService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->livrablesRealisationService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $livrablesRealisation = $this->livrablesRealisationService->find($id);
        $this->authorize('delete', $livrablesRealisation);

        $livrablesRealisation = $this->livrablesRealisationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' =>  __('PkgRealisationProjets::livrablesRealisation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('livrablesRealisations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' =>  __('PkgRealisationProjets::livrablesRealisation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $livrablesRealisation_ids = $request->input('ids', []);
        if (!is_array($livrablesRealisation_ids) || count($livrablesRealisation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($livrablesRealisation_ids as $id) {
            $entity = $this->livrablesRealisationService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $livrablesRealisation = $this->livrablesRealisationService->find($id);
            $this->authorize('delete', $livrablesRealisation);
            $this->livrablesRealisationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($livrablesRealisation_ids) . ' éléments',
            'modelName' => __('PkgRealisationProjets::livrablesRealisation.plural')
        ]));
    }

    public function export($format)
    {
        $livrablesRealisations_data = $this->livrablesRealisationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new LivrablesRealisationExport($livrablesRealisations_data,'csv'), 'livrablesRealisation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new LivrablesRealisationExport($livrablesRealisations_data,'xlsx'), 'livrablesRealisation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new LivrablesRealisationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('livrablesRealisations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('livrablesRealisations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationProjets::livrablesRealisation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getLivrablesRealisations()
    {
        $livrablesRealisations = $this->livrablesRealisationService->all();
        return response()->json($livrablesRealisations);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $livrablesRealisation = $this->livrablesRealisationService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedLivrablesRealisation = $this->livrablesRealisationService->dataCalcul($livrablesRealisation);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedLivrablesRealisation
        ]);
    }
    


    /**
     * @DynamicPermissionIgnore
     * Met à jour les attributs, il est utilisé par type View : Widgets
     */
    public function updateAttributes(Request $request)
    {
        // Autorisation dynamique basée sur le nom du contrôleur
        $this->authorizeAction('update');
    
        $updatableFields = $this->service->getFieldsEditable();
        $livrablesRealisationRequest = new LivrablesRealisationRequest();
        $fullRules = $livrablesRealisationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:livrables_realisations,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.', 422);
        }
    
        $this->getService()->update($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}