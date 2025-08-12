<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\SpecialiteService;
use Modules\PkgFormation\Services\FormateurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgFormation\App\Requests\SpecialiteRequest;
use Modules\PkgFormation\Models\Specialite;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgFormation\App\Exports\SpecialiteExport;
use Modules\PkgFormation\App\Imports\SpecialiteImport;
use Modules\Core\Services\ContextState;

class BaseSpecialiteController extends AdminController
{
    protected $specialiteService;
    protected $formateurService;

    public function __construct(SpecialiteService $specialiteService, FormateurService $formateurService) {
        parent::__construct();
        $this->service  =  $specialiteService;
        $this->specialiteService = $specialiteService;
        $this->formateurService = $formateurService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('specialite.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('specialite');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $specialites_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'specialites_search',
                $this->viewState->get("filter.specialite.specialites_search")
            )],
            $request->except(['specialites_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->specialiteService->prepareDataForIndexView($specialites_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgFormation::specialite._index', $specialite_compact_value)->render();
            }else{
                return view($specialite_partialViewName, $specialite_compact_value)->render();
            }
        }

        return view('PkgFormation::specialite.index', $specialite_compact_value);
    }
    /**
     */
    public function create() {


        $itemSpecialite = $this->specialiteService->createInstance();
        

        $formateurs = $this->formateurService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgFormation::specialite._fields', compact('bulkEdit' ,'itemSpecialite', 'formateurs'));
        }
        return view('PkgFormation::specialite.create', compact('bulkEdit' ,'itemSpecialite', 'formateurs'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $specialite_ids = $request->input('ids', []);

        if (!is_array($specialite_ids) || count($specialite_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemSpecialite = $this->specialiteService->find($specialite_ids[0]);
         
 
        $formateurs = $this->formateurService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemSpecialite = $this->specialiteService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgFormation::specialite._fields', compact('bulkEdit', 'specialite_ids', 'itemSpecialite', 'formateurs'));
        }
        return view('PkgFormation::specialite.bulk-edit', compact('bulkEdit', 'specialite_ids', 'itemSpecialite', 'formateurs'));
    }
    /**
     */
    public function store(SpecialiteRequest $request) {
        $validatedData = $request->validated();
        $specialite = $this->specialiteService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $specialite,
                'modelName' => __('PkgFormation::specialite.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $specialite->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('specialites.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $specialite,
                'modelName' => __('PkgFormation::specialite.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('specialite.show_' . $id);

        $itemSpecialite = $this->specialiteService->edit($id);


        if (request()->ajax()) {
            return view('PkgFormation::specialite._show', array_merge(compact('itemSpecialite'),));
        }

        return view('PkgFormation::specialite.show', array_merge(compact('itemSpecialite'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('specialite.edit_' . $id);


        $itemSpecialite = $this->specialiteService->edit($id);


        $formateurs = $this->formateurService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgFormation::specialite._fields', array_merge(compact('bulkEdit' , 'itemSpecialite','formateurs'),));
        }

        return view('PkgFormation::specialite.edit', array_merge(compact('bulkEdit' ,'itemSpecialite','formateurs'),));


    }
    /**
     */
    public function update(SpecialiteRequest $request, string $id) {

        $validatedData = $request->validated();
        $specialite = $this->specialiteService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgFormation::specialite.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $specialite->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('specialites.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgFormation::specialite.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $specialite_ids = $request->input('specialite_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($specialite_ids) || count($specialite_ids) === 0) {
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
            $specialite_ids,
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

        $specialite = $this->specialiteService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgFormation::specialite.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('specialites.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgFormation::specialite.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $specialite_ids = $request->input('ids', []);
        if (!is_array($specialite_ids) || count($specialite_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($specialite_ids as $id) {
            $entity = $this->specialiteService->find($id);
            $this->specialiteService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($specialite_ids) . ' éléments',
            'modelName' => __('PkgFormation::specialite.plural')
        ]));
    }

    public function export($format)
    {
        $specialites_data = $this->specialiteService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new SpecialiteExport($specialites_data,'csv'), 'specialite_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new SpecialiteExport($specialites_data,'xlsx'), 'specialite_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new SpecialiteImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('specialites.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('specialites.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgFormation::specialite.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSpecialites()
    {
        $specialites = $this->specialiteService->all();
        return response()->json($specialites);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Specialite) par ID, en format JSON.
     */
    public function getSpecialite(Request $request, $id)
    {
        try {
            $specialite = $this->specialiteService->find($id);
            return response()->json($specialite);
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
        $updatedSpecialite = $this->specialiteService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedSpecialite],
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
        $specialiteRequest = new SpecialiteRequest();
        $fullRules = $specialiteRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:specialites,id'];
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