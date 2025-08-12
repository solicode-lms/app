<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgSessions\Controllers\Base;
use Modules\PkgSessions\Services\AlignementUaService;
use Modules\PkgSessions\Services\SessionFormationService;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgSessions\App\Requests\AlignementUaRequest;
use Modules\PkgSessions\Models\AlignementUa;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgSessions\App\Exports\AlignementUaExport;
use Modules\PkgSessions\App\Imports\AlignementUaImport;
use Modules\Core\Services\ContextState;

class BaseAlignementUaController extends AdminController
{
    protected $alignementUaService;
    protected $sessionFormationService;
    protected $uniteApprentissageService;

    public function __construct(AlignementUaService $alignementUaService, SessionFormationService $sessionFormationService, UniteApprentissageService $uniteApprentissageService) {
        parent::__construct();
        $this->service  =  $alignementUaService;
        $this->alignementUaService = $alignementUaService;
        $this->sessionFormationService = $sessionFormationService;
        $this->uniteApprentissageService = $uniteApprentissageService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('alignementUa.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('alignementUa');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $alignementUas_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'alignementUas_search',
                $this->viewState->get("filter.alignementUa.alignementUas_search")
            )],
            $request->except(['alignementUas_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->alignementUaService->prepareDataForIndexView($alignementUas_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgSessions::alignementUa._index', $alignementUa_compact_value)->render();
            }else{
                return view($alignementUa_partialViewName, $alignementUa_compact_value)->render();
            }
        }

        return view('PkgSessions::alignementUa.index', $alignementUa_compact_value);
    }
    /**
     */
    public function create() {


        $itemAlignementUa = $this->alignementUaService->createInstance();
        

        $uniteApprentissages = $this->uniteApprentissageService->all();
        $sessionFormations = $this->sessionFormationService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgSessions::alignementUa._fields', compact('bulkEdit' ,'itemAlignementUa', 'sessionFormations', 'uniteApprentissages'));
        }
        return view('PkgSessions::alignementUa.create', compact('bulkEdit' ,'itemAlignementUa', 'sessionFormations', 'uniteApprentissages'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $alignementUa_ids = $request->input('ids', []);

        if (!is_array($alignementUa_ids) || count($alignementUa_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemAlignementUa = $this->alignementUaService->find($alignementUa_ids[0]);
         
 
        $uniteApprentissages = $this->uniteApprentissageService->all();
        $sessionFormations = $this->sessionFormationService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemAlignementUa = $this->alignementUaService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgSessions::alignementUa._fields', compact('bulkEdit', 'alignementUa_ids', 'itemAlignementUa', 'sessionFormations', 'uniteApprentissages'));
        }
        return view('PkgSessions::alignementUa.bulk-edit', compact('bulkEdit', 'alignementUa_ids', 'itemAlignementUa', 'sessionFormations', 'uniteApprentissages'));
    }
    /**
     */
    public function store(AlignementUaRequest $request) {
        $validatedData = $request->validated();
        $alignementUa = $this->alignementUaService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $alignementUa,
                'modelName' => __('PkgSessions::alignementUa.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $alignementUa->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('alignementUas.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $alignementUa,
                'modelName' => __('PkgSessions::alignementUa.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('alignementUa.show_' . $id);

        $itemAlignementUa = $this->alignementUaService->edit($id);


        if (request()->ajax()) {
            return view('PkgSessions::alignementUa._show', array_merge(compact('itemAlignementUa'),));
        }

        return view('PkgSessions::alignementUa.show', array_merge(compact('itemAlignementUa'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('alignementUa.edit_' . $id);


        $itemAlignementUa = $this->alignementUaService->edit($id);


        $uniteApprentissages = $this->uniteApprentissageService->all();
        $sessionFormations = $this->sessionFormationService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgSessions::alignementUa._fields', array_merge(compact('bulkEdit' , 'itemAlignementUa','sessionFormations', 'uniteApprentissages'),));
        }

        return view('PkgSessions::alignementUa.edit', array_merge(compact('bulkEdit' ,'itemAlignementUa','sessionFormations', 'uniteApprentissages'),));


    }
    /**
     */
    public function update(AlignementUaRequest $request, string $id) {

        $validatedData = $request->validated();
        $alignementUa = $this->alignementUaService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $alignementUa,
                'modelName' =>  __('PkgSessions::alignementUa.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $alignementUa->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('alignementUas.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $alignementUa,
                'modelName' =>  __('PkgSessions::alignementUa.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $alignementUa_ids = $request->input('alignementUa_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($alignementUa_ids) || count($alignementUa_ids) === 0) {
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
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $alignementUa_ids,
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

        $alignementUa = $this->alignementUaService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $alignementUa,
                'modelName' =>  __('PkgSessions::alignementUa.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('alignementUas.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $alignementUa,
                'modelName' =>  __('PkgSessions::alignementUa.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $alignementUa_ids = $request->input('ids', []);
        if (!is_array($alignementUa_ids) || count($alignementUa_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($alignementUa_ids as $id) {
            $entity = $this->alignementUaService->find($id);
            $this->alignementUaService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($alignementUa_ids) . ' éléments',
            'modelName' => __('PkgSessions::alignementUa.plural')
        ]));
    }

    public function export($format)
    {
        $alignementUas_data = $this->alignementUaService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new AlignementUaExport($alignementUas_data,'csv'), 'alignementUa_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new AlignementUaExport($alignementUas_data,'xlsx'), 'alignementUa_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new AlignementUaImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('alignementUas.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('alignementUas.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgSessions::alignementUa.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getAlignementUas()
    {
        $alignementUas = $this->alignementUaService->all();
        return response()->json($alignementUas);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (AlignementUa) par ID, en format JSON.
     */
    public function getAlignementUa(Request $request, $id)
    {
        try {
            $alignementUa = $this->alignementUaService->find($id);
            return response()->json($alignementUa);
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
        $updatedAlignementUa = $this->alignementUaService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedAlignementUa],
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
        $alignementUaRequest = new AlignementUaRequest();
        $fullRules = $alignementUaRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:alignement_uas,id'];
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