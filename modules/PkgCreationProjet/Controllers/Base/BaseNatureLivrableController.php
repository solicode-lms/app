<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\NatureLivrableService;
use Modules\PkgSessions\Services\LivrableSessionService;
use Modules\PkgCreationProjet\Services\LivrableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationProjet\App\Requests\NatureLivrableRequest;
use Modules\PkgCreationProjet\Models\NatureLivrable;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationProjet\App\Exports\NatureLivrableExport;
use Modules\PkgCreationProjet\App\Imports\NatureLivrableImport;
use Modules\Core\Services\ContextState;

class BaseNatureLivrableController extends AdminController
{
    protected $natureLivrableService;

    public function __construct(NatureLivrableService $natureLivrableService) {
        parent::__construct();
        $this->service  =  $natureLivrableService;
        $this->natureLivrableService = $natureLivrableService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('natureLivrable.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('natureLivrable');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $natureLivrables_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'natureLivrables_search',
                $this->viewState->get("filter.natureLivrable.natureLivrables_search")
            )],
            $request->except(['natureLivrables_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->natureLivrableService->prepareDataForIndexView($natureLivrables_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCreationProjet::natureLivrable._index', $natureLivrable_compact_value)->render();
            }else{
                return view($natureLivrable_partialViewName, $natureLivrable_compact_value)->render();
            }
        }

        return view('PkgCreationProjet::natureLivrable.index', $natureLivrable_compact_value);
    }
    /**
     */
    public function create() {


        $itemNatureLivrable = $this->natureLivrableService->createInstance();
        


        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCreationProjet::natureLivrable._fields', compact('bulkEdit' ,'itemNatureLivrable'));
        }
        return view('PkgCreationProjet::natureLivrable.create', compact('bulkEdit' ,'itemNatureLivrable'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $natureLivrable_ids = $request->input('ids', []);

        if (!is_array($natureLivrable_ids) || count($natureLivrable_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemNatureLivrable = $this->natureLivrableService->find($natureLivrable_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemNatureLivrable = $this->natureLivrableService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCreationProjet::natureLivrable._fields', compact('bulkEdit', 'natureLivrable_ids', 'itemNatureLivrable'));
        }
        return view('PkgCreationProjet::natureLivrable.bulk-edit', compact('bulkEdit', 'natureLivrable_ids', 'itemNatureLivrable'));
    }
    /**
     */
    public function store(NatureLivrableRequest $request) {
        $validatedData = $request->validated();
        $natureLivrable = $this->natureLivrableService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' => __('PkgCreationProjet::natureLivrable.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $natureLivrable->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('natureLivrables.edit', ['natureLivrable' => $natureLivrable->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' => __('PkgCreationProjet::natureLivrable.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('natureLivrable.show_' . $id);

        $itemNatureLivrable = $this->natureLivrableService->edit($id);


        $this->viewState->set('scope.livrableSession.nature_livrable_id', $id);
        

        $livrableSessionService =  new LivrableSessionService();
        $livrableSessions_view_data = $livrableSessionService->prepareDataForIndexView();
        extract($livrableSessions_view_data);

        $this->viewState->set('scope.livrable.nature_livrable_id', $id);
        

        $livrableService =  new LivrableService();
        $livrables_view_data = $livrableService->prepareDataForIndexView();
        extract($livrables_view_data);

        if (request()->ajax()) {
            return view('PkgCreationProjet::natureLivrable._show', array_merge(compact('itemNatureLivrable'),$livrableSession_compact_value, $livrable_compact_value));
        }

        return view('PkgCreationProjet::natureLivrable.show', array_merge(compact('itemNatureLivrable'),$livrableSession_compact_value, $livrable_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('natureLivrable.edit_' . $id);


        $itemNatureLivrable = $this->natureLivrableService->edit($id);




        $this->viewState->set('scope.livrableSession.nature_livrable_id', $id);
        

        $livrableSessionService =  new LivrableSessionService();
        $livrableSessions_view_data = $livrableSessionService->prepareDataForIndexView();
        extract($livrableSessions_view_data);

        $this->viewState->set('scope.livrable.nature_livrable_id', $id);
        

        $livrableService =  new LivrableService();
        $livrables_view_data = $livrableService->prepareDataForIndexView();
        extract($livrables_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCreationProjet::natureLivrable._edit', array_merge(compact('bulkEdit' , 'itemNatureLivrable',),$livrableSession_compact_value, $livrable_compact_value));
        }

        return view('PkgCreationProjet::natureLivrable.edit', array_merge(compact('bulkEdit' ,'itemNatureLivrable',),$livrableSession_compact_value, $livrable_compact_value));


    }
    /**
     */
    public function update(NatureLivrableRequest $request, string $id) {

        $validatedData = $request->validated();
        $natureLivrable = $this->natureLivrableService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' =>  __('PkgCreationProjet::natureLivrable.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $natureLivrable->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('natureLivrables.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' =>  __('PkgCreationProjet::natureLivrable.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $natureLivrable_ids = $request->input('natureLivrable_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($natureLivrable_ids) || count($natureLivrable_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }

        // 🔹 Récupérer les valeurs de ces champs
        $valeursChamps = [];
        foreach ($champsCoches as $field) {
            $valeursChamps[$field] = $request->input($field);
        }

        $jobManager = new JobManager();
        $jobManager->init("bulkUpdateJob",$this->service->modelName,$this->service->moduleName);
         
        dispatch(new BulkEditJob(
            Auth::id(),
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $natureLivrable_ids,
            $champsCoches,
            $valeursChamps
        ));

       
        return JsonResponseHelper::success(
             __('Mise à jour en masse effectuée avec succès.'),
                ['traitement_token' => $jobManager->getToken()]
        );

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $natureLivrable = $this->natureLivrableService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' =>  __('PkgCreationProjet::natureLivrable.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('natureLivrables.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' =>  __('PkgCreationProjet::natureLivrable.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $natureLivrable_ids = $request->input('ids', []);
        if (!is_array($natureLivrable_ids) || count($natureLivrable_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($natureLivrable_ids as $id) {
            $entity = $this->natureLivrableService->find($id);
            $this->natureLivrableService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($natureLivrable_ids) . ' éléments',
            'modelName' => __('PkgCreationProjet::natureLivrable.plural')
        ]));
    }

    public function export($format)
    {
        $natureLivrables_data = $this->natureLivrableService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new NatureLivrableExport($natureLivrables_data,'csv'), 'natureLivrable_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new NatureLivrableExport($natureLivrables_data,'xlsx'), 'natureLivrable_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new NatureLivrableImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('natureLivrables.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('natureLivrables.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::natureLivrable.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNatureLivrables()
    {
        $natureLivrables = $this->natureLivrableService->all();
        return response()->json($natureLivrables);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (NatureLivrable) par ID, en format JSON.
     */
    public function getNatureLivrable(Request $request, $id)
    {
        try {
            $natureLivrable = $this->natureLivrableService->find($id);
            return response()->json($natureLivrable);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Entité non trouvée ou erreur.',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
    public function dataCalcul(Request $request)
    {
        $data = $request->all();

        // Traitement métier personnalisé (ne modifie pas la base)
        $updatedNatureLivrable = $this->natureLivrableService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedNatureLivrable],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
        ));
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
        $natureLivrableRequest = new NatureLivrableRequest();
        $fullRules = $natureLivrableRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:nature_livrables,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.',null, 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(
             __('Mise à jour réussie.'),
                array_merge(
                    ['entity_id' => $validated['id']],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
        );
    }

    /**
     * Retourne les métadonnées d’un champ (type, options, validation, etag…)
     *  @DynamicPermissionIgnore
     */
    public function fieldMeta(int $id, string $field)
    {
        // $this->authorizeAction('update');
        $itemNatureLivrable = NatureLivrable::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemNatureLivrable, $field);
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
        $itemNatureLivrable = NatureLivrable::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemNatureLivrable);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemNatureLivrable, $changes);

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