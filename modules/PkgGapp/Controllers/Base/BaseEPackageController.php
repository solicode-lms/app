<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EPackageService;
use Modules\PkgGapp\Services\EModelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGapp\App\Requests\EPackageRequest;
use Modules\PkgGapp\Models\EPackage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\EPackageExport;
use Modules\PkgGapp\App\Imports\EPackageImport;
use Modules\Core\Services\ContextState;

class BaseEPackageController extends AdminController
{
    protected $ePackageService;

    public function __construct(EPackageService $ePackageService) {
        parent::__construct();
        $this->service  =  $ePackageService;
        $this->ePackageService = $ePackageService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('ePackage.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('ePackage');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $ePackages_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'ePackages_search',
                $this->viewState->get("filter.ePackage.ePackages_search")
            )],
            $request->except(['ePackages_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->ePackageService->prepareDataForIndexView($ePackages_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgGapp::ePackage._index', $ePackage_compact_value)->render();
            }else{
                return view($ePackage_partialViewName, $ePackage_compact_value)->render();
            }
        }

        return view('PkgGapp::ePackage.index', $ePackage_compact_value);
    }
    /**
     */
    public function create() {


        $itemEPackage = $this->ePackageService->createInstance();
        


        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgGapp::ePackage._fields', compact('bulkEdit' ,'itemEPackage'));
        }
        return view('PkgGapp::ePackage.create', compact('bulkEdit' ,'itemEPackage'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $ePackage_ids = $request->input('ids', []);

        if (!is_array($ePackage_ids) || count($ePackage_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEPackage = $this->ePackageService->find($ePackage_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEPackage = $this->ePackageService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgGapp::ePackage._fields', compact('bulkEdit', 'ePackage_ids', 'itemEPackage'));
        }
        return view('PkgGapp::ePackage.bulk-edit', compact('bulkEdit', 'ePackage_ids', 'itemEPackage'));
    }
    /**
     */
    public function store(EPackageRequest $request) {
        $validatedData = $request->validated();
        $ePackage = $this->ePackageService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $ePackage,
                'modelName' => __('PkgGapp::ePackage.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $ePackage->id]
            );
        }

        return redirect()->route('ePackages.edit',['ePackage' => $ePackage->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $ePackage,
                'modelName' => __('PkgGapp::ePackage.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('ePackage.show_' . $id);

        $itemEPackage = $this->ePackageService->edit($id);


        $this->viewState->set('scope.eModel.e_package_id', $id);
        

        $eModelService =  new EModelService();
        $eModels_view_data = $eModelService->prepareDataForIndexView();
        extract($eModels_view_data);

        if (request()->ajax()) {
            return view('PkgGapp::ePackage._show', array_merge(compact('itemEPackage'),$eModel_compact_value));
        }

        return view('PkgGapp::ePackage.show', array_merge(compact('itemEPackage'),$eModel_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('ePackage.edit_' . $id);


        $itemEPackage = $this->ePackageService->edit($id);




        $this->viewState->set('scope.eModel.e_package_id', $id);
        

        $eModelService =  new EModelService();
        $eModels_view_data = $eModelService->prepareDataForIndexView();
        extract($eModels_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgGapp::ePackage._edit', array_merge(compact('bulkEdit' , 'itemEPackage',),$eModel_compact_value));
        }

        return view('PkgGapp::ePackage.edit', array_merge(compact('bulkEdit' ,'itemEPackage',),$eModel_compact_value));


    }
    /**
     */
    public function update(EPackageRequest $request, string $id) {

        $validatedData = $request->validated();
        $ePackage = $this->ePackageService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $ePackage,
                'modelName' =>  __('PkgGapp::ePackage.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $ePackage->id]
            );
        }

        return redirect()->route('ePackages.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $ePackage,
                'modelName' =>  __('PkgGapp::ePackage.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $ePackage_ids = $request->input('ePackage_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($ePackage_ids) || count($ePackage_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($ePackage_ids as $id) {
            $entity = $this->ePackageService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->ePackageService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->ePackageService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $ePackage = $this->ePackageService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $ePackage,
                'modelName' =>  __('PkgGapp::ePackage.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('ePackages.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $ePackage,
                'modelName' =>  __('PkgGapp::ePackage.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $ePackage_ids = $request->input('ids', []);
        if (!is_array($ePackage_ids) || count($ePackage_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($ePackage_ids as $id) {
            $entity = $this->ePackageService->find($id);
            $this->ePackageService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($ePackage_ids) . ' éléments',
            'modelName' => __('PkgGapp::ePackage.plural')
        ]));
    }

    public function export($format)
    {
        $ePackages_data = $this->ePackageService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EPackageExport($ePackages_data,'csv'), 'ePackage_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EPackageExport($ePackages_data,'xlsx'), 'ePackage_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EPackageImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('ePackages.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('ePackages.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::ePackage.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEPackages()
    {
        $ePackages = $this->ePackageService->all();
        return response()->json($ePackages);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EPackage) par ID, en format JSON.
     */
    public function getEPackage(Request $request, $id)
    {
        try {
            $ePackage = $this->ePackageService->find($id);
            return response()->json($ePackage);
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
        $updatedEPackage = $this->ePackageService->dataCalcul($data);

        return response()->json([
            'success' => true,
            'entity' => $updatedEPackage
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
        $ePackageRequest = new EPackageRequest();
        $fullRules = $ePackageRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:e_packages,id'];
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