<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EMetadataDefinitionService;
use Modules\PkgGapp\Services\EMetadatumService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGapp\App\Requests\EMetadataDefinitionRequest;
use Modules\PkgGapp\Models\EMetadataDefinition;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\EMetadataDefinitionExport;
use Modules\PkgGapp\App\Imports\EMetadataDefinitionImport;
use Modules\Core\Services\ContextState;

class BaseEMetadataDefinitionController extends AdminController
{
    protected $eMetadataDefinitionService;

    public function __construct(EMetadataDefinitionService $eMetadataDefinitionService) {
        parent::__construct();
        $this->service  =  $eMetadataDefinitionService;
        $this->eMetadataDefinitionService = $eMetadataDefinitionService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('eMetadataDefinition.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('eMetadataDefinition');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $eMetadataDefinitions_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'eMetadataDefinitions_search',
                $this->viewState->get("filter.eMetadataDefinition.eMetadataDefinitions_search")
            )],
            $request->except(['eMetadataDefinitions_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->eMetadataDefinitionService->prepareDataForIndexView($eMetadataDefinitions_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgGapp::eMetadataDefinition._index', $eMetadataDefinition_compact_value)->render();
            }else{
                return view($eMetadataDefinition_partialViewName, $eMetadataDefinition_compact_value)->render();
            }
        }

        return view('PkgGapp::eMetadataDefinition.index', $eMetadataDefinition_compact_value);
    }
    /**
     */
    public function create() {


        $itemEMetadataDefinition = $this->eMetadataDefinitionService->createInstance();
        


        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgGapp::eMetadataDefinition._fields', compact('bulkEdit' ,'itemEMetadataDefinition'));
        }
        return view('PkgGapp::eMetadataDefinition.create', compact('bulkEdit' ,'itemEMetadataDefinition'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $eMetadataDefinition_ids = $request->input('ids', []);

        if (!is_array($eMetadataDefinition_ids) || count($eMetadataDefinition_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEMetadataDefinition = $this->eMetadataDefinitionService->find($eMetadataDefinition_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEMetadataDefinition = $this->eMetadataDefinitionService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgGapp::eMetadataDefinition._fields', compact('bulkEdit', 'eMetadataDefinition_ids', 'itemEMetadataDefinition'));
        }
        return view('PkgGapp::eMetadataDefinition.bulk-edit', compact('bulkEdit', 'eMetadataDefinition_ids', 'itemEMetadataDefinition'));
    }
    /**
     */
    public function store(EMetadataDefinitionRequest $request) {
        $validatedData = $request->validated();
        $eMetadataDefinition = $this->eMetadataDefinitionService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' => __('PkgGapp::eMetadataDefinition.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $eMetadataDefinition->id]
            );
        }

        return redirect()->route('eMetadataDefinitions.edit',['eMetadataDefinition' => $eMetadataDefinition->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' => __('PkgGapp::eMetadataDefinition.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('eMetadataDefinition.show_' . $id);

        $itemEMetadataDefinition = $this->eMetadataDefinitionService->edit($id);


        $this->viewState->set('scope.eMetadatum.e_metadata_definition_id', $id);
        

        $eMetadatumService =  new EMetadatumService();
        $eMetadata_view_data = $eMetadatumService->prepareDataForIndexView();
        extract($eMetadata_view_data);

        if (request()->ajax()) {
            return view('PkgGapp::eMetadataDefinition._show', array_merge(compact('itemEMetadataDefinition'),$eMetadatum_compact_value));
        }

        return view('PkgGapp::eMetadataDefinition.show', array_merge(compact('itemEMetadataDefinition'),$eMetadatum_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('eMetadataDefinition.edit_' . $id);


        $itemEMetadataDefinition = $this->eMetadataDefinitionService->edit($id);




        $this->viewState->set('scope.eMetadatum.e_metadata_definition_id', $id);
        

        $eMetadatumService =  new EMetadatumService();
        $eMetadata_view_data = $eMetadatumService->prepareDataForIndexView();
        extract($eMetadata_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgGapp::eMetadataDefinition._edit', array_merge(compact('bulkEdit' , 'itemEMetadataDefinition',),$eMetadatum_compact_value));
        }

        return view('PkgGapp::eMetadataDefinition.edit', array_merge(compact('bulkEdit' ,'itemEMetadataDefinition',),$eMetadatum_compact_value));


    }
    /**
     */
    public function update(EMetadataDefinitionRequest $request, string $id) {

        $validatedData = $request->validated();
        $eMetadataDefinition = $this->eMetadataDefinitionService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' =>  __('PkgGapp::eMetadataDefinition.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $eMetadataDefinition->id]
            );
        }

        return redirect()->route('eMetadataDefinitions.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' =>  __('PkgGapp::eMetadataDefinition.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $eMetadataDefinition_ids = $request->input('eMetadataDefinition_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($eMetadataDefinition_ids) || count($eMetadataDefinition_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($eMetadataDefinition_ids as $id) {
            $entity = $this->eMetadataDefinitionService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->eMetadataDefinitionService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->eMetadataDefinitionService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $eMetadataDefinition = $this->eMetadataDefinitionService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' =>  __('PkgGapp::eMetadataDefinition.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('eMetadataDefinitions.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eMetadataDefinition,
                'modelName' =>  __('PkgGapp::eMetadataDefinition.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $eMetadataDefinition_ids = $request->input('ids', []);
        if (!is_array($eMetadataDefinition_ids) || count($eMetadataDefinition_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($eMetadataDefinition_ids as $id) {
            $entity = $this->eMetadataDefinitionService->find($id);
            $this->eMetadataDefinitionService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($eMetadataDefinition_ids) . ' éléments',
            'modelName' => __('PkgGapp::eMetadataDefinition.plural')
        ]));
    }

    public function export($format)
    {
        $eMetadataDefinitions_data = $this->eMetadataDefinitionService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EMetadataDefinitionExport($eMetadataDefinitions_data,'csv'), 'eMetadataDefinition_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EMetadataDefinitionExport($eMetadataDefinitions_data,'xlsx'), 'eMetadataDefinition_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EMetadataDefinitionImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('eMetadataDefinitions.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('eMetadataDefinitions.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::eMetadataDefinition.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEMetadataDefinitions()
    {
        $eMetadataDefinitions = $this->eMetadataDefinitionService->all();
        return response()->json($eMetadataDefinitions);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EMetadataDefinition) par ID, en format JSON.
     */
    public function getEMetadataDefinition(Request $request, $id)
    {
        try {
            $eMetadataDefinition = $this->eMetadataDefinitionService->find($id);
            return response()->json($eMetadataDefinition);
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
        $updatedEMetadataDefinition = $this->eMetadataDefinitionService->dataCalcul($data);

        return response()->json([
            'success' => true,
            'entity' => $updatedEMetadataDefinition
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
        $eMetadataDefinitionRequest = new EMetadataDefinitionRequest();
        $fullRules = $eMetadataDefinitionRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:e_metadata_definitions,id'];
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