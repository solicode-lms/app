<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\MobilisationUaService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationProjet\App\Requests\MobilisationUaRequest;
use Modules\PkgCreationProjet\Models\MobilisationUa;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationProjet\App\Exports\MobilisationUaExport;
use Modules\PkgCreationProjet\App\Imports\MobilisationUaImport;
use Modules\Core\Services\ContextState;

class BaseMobilisationUaController extends AdminController
{
    protected $mobilisationUaService;
    protected $projetService;
    protected $uniteApprentissageService;

    public function __construct(MobilisationUaService $mobilisationUaService, ProjetService $projetService, UniteApprentissageService $uniteApprentissageService) {
        parent::__construct();
        $this->service  =  $mobilisationUaService;
        $this->mobilisationUaService = $mobilisationUaService;
        $this->projetService = $projetService;
        $this->uniteApprentissageService = $uniteApprentissageService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('mobilisationUa.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('mobilisationUa');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $mobilisationUas_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'mobilisationUas_search',
                $this->viewState->get("filter.mobilisationUa.mobilisationUas_search")
            )],
            $request->except(['mobilisationUas_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->mobilisationUaService->prepareDataForIndexView($mobilisationUas_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCreationProjet::mobilisationUa._index', $mobilisationUa_compact_value)->render();
            }else{
                return view($mobilisationUa_partialViewName, $mobilisationUa_compact_value)->render();
            }
        }

        return view('PkgCreationProjet::mobilisationUa.index', $mobilisationUa_compact_value);
    }
    /**
     */
    public function create() {


        $itemMobilisationUa = $this->mobilisationUaService->createInstance();
        

        $uniteApprentissages = $this->uniteApprentissageService->all();
        $projets = $this->projetService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCreationProjet::mobilisationUa._fields', compact('bulkEdit' ,'itemMobilisationUa', 'projets', 'uniteApprentissages'));
        }
        return view('PkgCreationProjet::mobilisationUa.create', compact('bulkEdit' ,'itemMobilisationUa', 'projets', 'uniteApprentissages'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $mobilisationUa_ids = $request->input('ids', []);

        if (!is_array($mobilisationUa_ids) || count($mobilisationUa_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemMobilisationUa = $this->mobilisationUaService->find($mobilisationUa_ids[0]);
         
 
        $uniteApprentissages = $this->uniteApprentissageService->all();
        $projets = $this->projetService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemMobilisationUa = $this->mobilisationUaService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCreationProjet::mobilisationUa._fields', compact('bulkEdit', 'mobilisationUa_ids', 'itemMobilisationUa', 'projets', 'uniteApprentissages'));
        }
        return view('PkgCreationProjet::mobilisationUa.bulk-edit', compact('bulkEdit', 'mobilisationUa_ids', 'itemMobilisationUa', 'projets', 'uniteApprentissages'));
    }
    /**
     */
    public function store(MobilisationUaRequest $request) {
        $validatedData = $request->validated();
        $mobilisationUa = $this->mobilisationUaService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $mobilisationUa,
                'modelName' => __('PkgCreationProjet::mobilisationUa.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $mobilisationUa->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('mobilisationUas.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $mobilisationUa,
                'modelName' => __('PkgCreationProjet::mobilisationUa.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('mobilisationUa.show_' . $id);

        $itemMobilisationUa = $this->mobilisationUaService->edit($id);


        if (request()->ajax()) {
            return view('PkgCreationProjet::mobilisationUa._show', array_merge(compact('itemMobilisationUa'),));
        }

        return view('PkgCreationProjet::mobilisationUa.show', array_merge(compact('itemMobilisationUa'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('mobilisationUa.edit_' . $id);


        $itemMobilisationUa = $this->mobilisationUaService->edit($id);


        $uniteApprentissages = $this->uniteApprentissageService->all();
        $projets = $this->projetService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCreationProjet::mobilisationUa._fields', array_merge(compact('bulkEdit' , 'itemMobilisationUa','projets', 'uniteApprentissages'),));
        }

        return view('PkgCreationProjet::mobilisationUa.edit', array_merge(compact('bulkEdit' ,'itemMobilisationUa','projets', 'uniteApprentissages'),));


    }
    /**
     */
    public function update(MobilisationUaRequest $request, string $id) {

        $validatedData = $request->validated();
        $mobilisationUa = $this->mobilisationUaService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $mobilisationUa,
                'modelName' =>  __('PkgCreationProjet::mobilisationUa.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $mobilisationUa->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('mobilisationUas.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $mobilisationUa,
                'modelName' =>  __('PkgCreationProjet::mobilisationUa.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $mobilisationUa_ids = $request->input('mobilisationUa_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($mobilisationUa_ids) || count($mobilisationUa_ids) === 0) {
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
            $mobilisationUa_ids,
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

        $mobilisationUa = $this->mobilisationUaService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $mobilisationUa,
                'modelName' =>  __('PkgCreationProjet::mobilisationUa.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('mobilisationUas.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $mobilisationUa,
                'modelName' =>  __('PkgCreationProjet::mobilisationUa.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $mobilisationUa_ids = $request->input('ids', []);
        if (!is_array($mobilisationUa_ids) || count($mobilisationUa_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($mobilisationUa_ids as $id) {
            $entity = $this->mobilisationUaService->find($id);
            $this->mobilisationUaService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($mobilisationUa_ids) . ' éléments',
            'modelName' => __('PkgCreationProjet::mobilisationUa.plural')
        ]));
    }

    public function export($format)
    {
        $mobilisationUas_data = $this->mobilisationUaService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new MobilisationUaExport($mobilisationUas_data,'csv'), 'mobilisationUa_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new MobilisationUaExport($mobilisationUas_data,'xlsx'), 'mobilisationUa_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new MobilisationUaImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('mobilisationUas.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('mobilisationUas.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::mobilisationUa.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getMobilisationUas()
    {
        $mobilisationUas = $this->mobilisationUaService->all();
        return response()->json($mobilisationUas);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (MobilisationUa) par ID, en format JSON.
     */
    public function getMobilisationUa(Request $request, $id)
    {
        try {
            $mobilisationUa = $this->mobilisationUaService->find($id);
            return response()->json($mobilisationUa);
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
        $updatedMobilisationUa = $this->mobilisationUaService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedMobilisationUa],
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
        $mobilisationUaRequest = new MobilisationUaRequest();
        $fullRules = $mobilisationUaRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:mobilisation_uas,id'];
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