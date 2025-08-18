<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationTache\Controllers\Base;
use Modules\PkgRealisationTache\Services\CommentaireRealisationTacheService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationTache\App\Requests\CommentaireRealisationTacheRequest;
use Modules\PkgRealisationTache\Models\CommentaireRealisationTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationTache\App\Exports\CommentaireRealisationTacheExport;
use Modules\PkgRealisationTache\App\Imports\CommentaireRealisationTacheImport;
use Modules\Core\Services\ContextState;

class BaseCommentaireRealisationTacheController extends AdminController
{
    protected $commentaireRealisationTacheService;
    protected $apprenantService;
    protected $formateurService;
    protected $realisationTacheService;

    public function __construct(CommentaireRealisationTacheService $commentaireRealisationTacheService, ApprenantService $apprenantService, FormateurService $formateurService, RealisationTacheService $realisationTacheService) {
        parent::__construct();
        $this->service  =  $commentaireRealisationTacheService;
        $this->commentaireRealisationTacheService = $commentaireRealisationTacheService;
        $this->apprenantService = $apprenantService;
        $this->formateurService = $formateurService;
        $this->realisationTacheService = $realisationTacheService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('commentaireRealisationTache.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('commentaireRealisationTache');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $commentaireRealisationTaches_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'commentaireRealisationTaches_search',
                $this->viewState->get("filter.commentaireRealisationTache.commentaireRealisationTaches_search")
            )],
            $request->except(['commentaireRealisationTaches_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->commentaireRealisationTacheService->prepareDataForIndexView($commentaireRealisationTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgRealisationTache::commentaireRealisationTache._index', $commentaireRealisationTache_compact_value)->render();
            }else{
                return view($commentaireRealisationTache_partialViewName, $commentaireRealisationTache_compact_value)->render();
            }
        }

        return view('PkgRealisationTache::commentaireRealisationTache.index', $commentaireRealisationTache_compact_value);
    }
    /**
     */
    public function create() {


        $itemCommentaireRealisationTache = $this->commentaireRealisationTacheService->createInstance();
        

        $realisationTaches = $this->realisationTacheService->all();
        $formateurs = $this->formateurService->all();
        $apprenants = $this->apprenantService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgRealisationTache::commentaireRealisationTache._fields', compact('bulkEdit' ,'itemCommentaireRealisationTache', 'apprenants', 'formateurs', 'realisationTaches'));
        }
        return view('PkgRealisationTache::commentaireRealisationTache.create', compact('bulkEdit' ,'itemCommentaireRealisationTache', 'apprenants', 'formateurs', 'realisationTaches'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $commentaireRealisationTache_ids = $request->input('ids', []);

        if (!is_array($commentaireRealisationTache_ids) || count($commentaireRealisationTache_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemCommentaireRealisationTache = $this->commentaireRealisationTacheService->find($commentaireRealisationTache_ids[0]);
         
 
        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemCommentaireRealisationTache->realisationTache);
        $formateurs = $this->formateurService->getAllForSelect($itemCommentaireRealisationTache->formateur);
        $apprenants = $this->apprenantService->getAllForSelect($itemCommentaireRealisationTache->apprenant);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemCommentaireRealisationTache = $this->commentaireRealisationTacheService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgRealisationTache::commentaireRealisationTache._fields', compact('bulkEdit', 'commentaireRealisationTache_ids', 'itemCommentaireRealisationTache', 'apprenants', 'formateurs', 'realisationTaches'));
        }
        return view('PkgRealisationTache::commentaireRealisationTache.bulk-edit', compact('bulkEdit', 'commentaireRealisationTache_ids', 'itemCommentaireRealisationTache', 'apprenants', 'formateurs', 'realisationTaches'));
    }
    /**
     */
    public function store(CommentaireRealisationTacheRequest $request) {
        $validatedData = $request->validated();
        $commentaireRealisationTache = $this->commentaireRealisationTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $commentaireRealisationTache,
                'modelName' => __('PkgRealisationTache::commentaireRealisationTache.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $commentaireRealisationTache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('commentaireRealisationTaches.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $commentaireRealisationTache,
                'modelName' => __('PkgRealisationTache::commentaireRealisationTache.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('commentaireRealisationTache.show_' . $id);

        $itemCommentaireRealisationTache = $this->commentaireRealisationTacheService->edit($id);


        if (request()->ajax()) {
            return view('PkgRealisationTache::commentaireRealisationTache._show', array_merge(compact('itemCommentaireRealisationTache'),));
        }

        return view('PkgRealisationTache::commentaireRealisationTache.show', array_merge(compact('itemCommentaireRealisationTache'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('commentaireRealisationTache.edit_' . $id);


        $itemCommentaireRealisationTache = $this->commentaireRealisationTacheService->edit($id);


        $realisationTaches = $this->realisationTacheService->getAllForSelect($itemCommentaireRealisationTache->realisationTache);
        $formateurs = $this->formateurService->getAllForSelect($itemCommentaireRealisationTache->formateur);
        $apprenants = $this->apprenantService->getAllForSelect($itemCommentaireRealisationTache->apprenant);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgRealisationTache::commentaireRealisationTache._fields', array_merge(compact('bulkEdit' , 'itemCommentaireRealisationTache','apprenants', 'formateurs', 'realisationTaches'),));
        }

        return view('PkgRealisationTache::commentaireRealisationTache.edit', array_merge(compact('bulkEdit' ,'itemCommentaireRealisationTache','apprenants', 'formateurs', 'realisationTaches'),));


    }
    /**
     */
    public function update(CommentaireRealisationTacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $commentaireRealisationTache = $this->commentaireRealisationTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $commentaireRealisationTache,
                'modelName' =>  __('PkgRealisationTache::commentaireRealisationTache.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $commentaireRealisationTache->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('commentaireRealisationTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $commentaireRealisationTache,
                'modelName' =>  __('PkgRealisationTache::commentaireRealisationTache.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $commentaireRealisationTache_ids = $request->input('commentaireRealisationTache_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($commentaireRealisationTache_ids) || count($commentaireRealisationTache_ids) === 0) {
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
            $commentaireRealisationTache_ids,
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

        $commentaireRealisationTache = $this->commentaireRealisationTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $commentaireRealisationTache,
                'modelName' =>  __('PkgRealisationTache::commentaireRealisationTache.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('commentaireRealisationTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $commentaireRealisationTache,
                'modelName' =>  __('PkgRealisationTache::commentaireRealisationTache.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $commentaireRealisationTache_ids = $request->input('ids', []);
        if (!is_array($commentaireRealisationTache_ids) || count($commentaireRealisationTache_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($commentaireRealisationTache_ids as $id) {
            $entity = $this->commentaireRealisationTacheService->find($id);
            $this->commentaireRealisationTacheService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($commentaireRealisationTache_ids) . ' éléments',
            'modelName' => __('PkgRealisationTache::commentaireRealisationTache.plural')
        ]));
    }

    public function export($format)
    {
        $commentaireRealisationTaches_data = $this->commentaireRealisationTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new CommentaireRealisationTacheExport($commentaireRealisationTaches_data,'csv'), 'commentaireRealisationTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new CommentaireRealisationTacheExport($commentaireRealisationTaches_data,'xlsx'), 'commentaireRealisationTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new CommentaireRealisationTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('commentaireRealisationTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('commentaireRealisationTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationTache::commentaireRealisationTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getCommentaireRealisationTaches()
    {
        $commentaireRealisationTaches = $this->commentaireRealisationTacheService->all();
        return response()->json($commentaireRealisationTaches);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (CommentaireRealisationTache) par ID, en format JSON.
     */
    public function getCommentaireRealisationTache(Request $request, $id)
    {
        try {
            $commentaireRealisationTache = $this->commentaireRealisationTacheService->find($id);
            return response()->json($commentaireRealisationTache);
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
        $updatedCommentaireRealisationTache = $this->commentaireRealisationTacheService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedCommentaireRealisationTache],
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
        $commentaireRealisationTacheRequest = new CommentaireRealisationTacheRequest();
        $fullRules = $commentaireRealisationTacheRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:commentaire_realisation_taches,id'];
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
}