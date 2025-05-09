<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\ModuleService;
use Modules\PkgFormation\Services\FiliereService;
use Modules\PkgCompetences\Services\CompetenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgFormation\App\Requests\ModuleRequest;
use Modules\PkgFormation\Models\Module;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgFormation\App\Exports\ModuleExport;
use Modules\PkgFormation\App\Imports\ModuleImport;
use Modules\Core\Services\ContextState;

class BaseModuleController extends AdminController
{
    protected $moduleService;
    protected $filiereService;

    public function __construct(ModuleService $moduleService, FiliereService $filiereService) {
        parent::__construct();
        $this->service  =  $moduleService;
        $this->moduleService = $moduleService;
        $this->filiereService = $filiereService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('module.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('module');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $modules_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'modules_search',
                $this->viewState->get("filter.module.modules_search")
            )],
            $request->except(['modules_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->moduleService->prepareDataForIndexView($modules_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgFormation::module._index', $module_compact_value)->render();
            }else{
                return view($module_partialViewName, $module_compact_value)->render();
            }
        }

        return view('PkgFormation::module.index', $module_compact_value);
    }
    /**
     */
    public function create() {


        $itemModule = $this->moduleService->createInstance();
        

        $filieres = $this->filiereService->all();

        if (request()->ajax()) {
            return view('PkgFormation::module._fields', compact('itemModule', 'filieres'));
        }
        return view('PkgFormation::module.create', compact('itemModule', 'filieres'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $module_ids = $request->input('ids', []);

        if (!is_array($module_ids) || count($module_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemModule = $this->moduleService->find($module_ids[0]);
         
 
        $filieres = $this->filiereService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemModule = $this->moduleService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgFormation::module._fields', compact('bulkEdit', 'module_ids', 'itemModule', 'filieres'));
        }
        return view('PkgFormation::module.bulk-edit', compact('bulkEdit', 'module_ids', 'itemModule', 'filieres'));
    }
    /**
     */
    public function store(ModuleRequest $request) {
        $validatedData = $request->validated();
        $module = $this->moduleService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $module,
                'modelName' => __('PkgFormation::module.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $module->id]
            );
        }

        return redirect()->route('modules.edit',['module' => $module->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $module,
                'modelName' => __('PkgFormation::module.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('module.show_' . $id);

        $itemModule = $this->moduleService->edit($id);


        $this->viewState->set('scope.competence.module_id', $id);
        

        $competenceService =  new CompetenceService();
        $competences_view_data = $competenceService->prepareDataForIndexView();
        extract($competences_view_data);

        if (request()->ajax()) {
            return view('PkgFormation::module._show', array_merge(compact('itemModule'),$competence_compact_value));
        }

        return view('PkgFormation::module.show', array_merge(compact('itemModule'),$competence_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('module.edit_' . $id);


        $itemModule = $this->moduleService->edit($id);


        $filieres = $this->filiereService->all();


        $this->viewState->set('scope.competence.module_id', $id);
        

        $competenceService =  new CompetenceService();
        $competences_view_data = $competenceService->prepareDataForIndexView();
        extract($competences_view_data);

        if (request()->ajax()) {
            return view('PkgFormation::module._edit', array_merge(compact('itemModule','filieres'),$competence_compact_value));
        }

        return view('PkgFormation::module.edit', array_merge(compact('itemModule','filieres'),$competence_compact_value));


    }
    /**
     */
    public function update(ModuleRequest $request, string $id) {

        $validatedData = $request->validated();
        $module = $this->moduleService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgFormation::module.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $module->id]
            );
        }

        return redirect()->route('modules.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgFormation::module.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $module_ids = $request->input('module_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($module_ids) || count($module_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($module_ids as $id) {
            $entity = $this->moduleService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->moduleService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->moduleService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $module = $this->moduleService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgFormation::module.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('modules.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $module,
                'modelName' =>  __('PkgFormation::module.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $module_ids = $request->input('ids', []);
        if (!is_array($module_ids) || count($module_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($module_ids as $id) {
            $entity = $this->moduleService->find($id);
            $this->moduleService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($module_ids) . ' éléments',
            'modelName' => __('PkgFormation::module.plural')
        ]));
    }

    public function export($format)
    {
        $modules_data = $this->moduleService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new ModuleExport($modules_data,'csv'), 'module_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new ModuleExport($modules_data,'xlsx'), 'module_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new ModuleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('modules.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('modules.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgFormation::module.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getModules()
    {
        $modules = $this->moduleService->all();
        return response()->json($modules);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $module = $this->moduleService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedModule = $this->moduleService->dataCalcul($module);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedModule
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
        $moduleRequest = new ModuleRequest();
        $fullRules = $moduleRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:modules,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.',null, 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}