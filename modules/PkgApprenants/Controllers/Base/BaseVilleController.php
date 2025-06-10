<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\VilleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprenants\App\Requests\VilleRequest;
use Modules\PkgApprenants\Models\Ville;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprenants\App\Exports\VilleExport;
use Modules\PkgApprenants\App\Imports\VilleImport;
use Modules\Core\Services\ContextState;

class BaseVilleController extends AdminController
{
    protected $villeService;

    public function __construct(VilleService $villeService) {
        parent::__construct();
        $this->service  =  $villeService;
        $this->villeService = $villeService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('ville.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('ville');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $villes_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'villes_search',
                $this->viewState->get("filter.ville.villes_search")
            )],
            $request->except(['villes_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->villeService->prepareDataForIndexView($villes_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprenants::ville._index', $ville_compact_value)->render();
            }else{
                return view($ville_partialViewName, $ville_compact_value)->render();
            }
        }

        return view('PkgApprenants::ville.index', $ville_compact_value);
    }
    /**
     */
    public function create() {


        $itemVille = $this->villeService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgApprenants::ville._fields', compact('itemVille'));
        }
        return view('PkgApprenants::ville.create', compact('itemVille'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $ville_ids = $request->input('ids', []);

        if (!is_array($ville_ids) || count($ville_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemVille = $this->villeService->find($ville_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemVille = $this->villeService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprenants::ville._fields', compact('bulkEdit', 'ville_ids', 'itemVille'));
        }
        return view('PkgApprenants::ville.bulk-edit', compact('bulkEdit', 'ville_ids', 'itemVille'));
    }
    /**
     */
    public function store(VilleRequest $request) {
        $validatedData = $request->validated();
        $ville = $this->villeService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $ville,
                'modelName' => __('PkgApprenants::ville.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $ville->id]
            );
        }

        return redirect()->route('villes.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $ville,
                'modelName' => __('PkgApprenants::ville.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('ville.show_' . $id);

        $itemVille = $this->villeService->edit($id);


        if (request()->ajax()) {
            return view('PkgApprenants::ville._show', array_merge(compact('itemVille'),));
        }

        return view('PkgApprenants::ville.show', array_merge(compact('itemVille'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('ville.edit_' . $id);


        $itemVille = $this->villeService->edit($id);




        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprenants::ville._fields', array_merge(compact('bulkEdit' , 'itemVille',),));
        }

        return view('PkgApprenants::ville.edit', array_merge(compact('bulkEdit' ,'itemVille',),));


    }
    /**
     */
    public function update(VilleRequest $request, string $id) {

        $validatedData = $request->validated();
        $ville = $this->villeService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgApprenants::ville.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $ville->id]
            );
        }

        return redirect()->route('villes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgApprenants::ville.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $ville_ids = $request->input('ville_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($ville_ids) || count($ville_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($ville_ids as $id) {
            $entity = $this->villeService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->villeService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->villeService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $ville = $this->villeService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgApprenants::ville.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('villes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgApprenants::ville.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $ville_ids = $request->input('ids', []);
        if (!is_array($ville_ids) || count($ville_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($ville_ids as $id) {
            $entity = $this->villeService->find($id);
            $this->villeService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($ville_ids) . ' éléments',
            'modelName' => __('PkgApprenants::ville.plural')
        ]));
    }

    public function export($format)
    {
        $villes_data = $this->villeService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new VilleExport($villes_data,'csv'), 'ville_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new VilleExport($villes_data,'xlsx'), 'ville_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new VilleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('villes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('villes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprenants::ville.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getVilles()
    {
        $villes = $this->villeService->all();
        return response()->json($villes);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $ville = $this->villeService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedVille = $this->villeService->dataCalcul($ville);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedVille
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
        $villeRequest = new VilleRequest();
        $fullRules = $villeRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:villes,id'];
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