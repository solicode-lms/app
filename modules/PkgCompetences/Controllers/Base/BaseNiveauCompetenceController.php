<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\NiveauCompetenceService;
use Modules\PkgCompetences\Services\CompetenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\NiveauCompetenceRequest;
use Modules\PkgCompetences\Models\NiveauCompetence;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\NiveauCompetenceExport;
use Modules\PkgCompetences\App\Imports\NiveauCompetenceImport;
use Modules\Core\Services\ContextState;

class BaseNiveauCompetenceController extends AdminController
{
    protected $niveauCompetenceService;
    protected $competenceService;

    public function __construct(NiveauCompetenceService $niveauCompetenceService, CompetenceService $competenceService) {
        parent::__construct();
        $this->service  =  $niveauCompetenceService;
        $this->niveauCompetenceService = $niveauCompetenceService;
        $this->competenceService = $competenceService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('niveauCompetence.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('niveauCompetence');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $niveauCompetences_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'niveauCompetences_search',
                $this->viewState->get("filter.niveauCompetence.niveauCompetences_search")
            )],
            $request->except(['niveauCompetences_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->niveauCompetenceService->prepareDataForIndexView($niveauCompetences_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::niveauCompetence._index', $niveauCompetence_compact_value)->render();
            }else{
                return view($niveauCompetence_partialViewName, $niveauCompetence_compact_value)->render();
            }
        }

        return view('PkgCompetences::niveauCompetence.index', $niveauCompetence_compact_value);
    }
    /**
     */
    public function create() {


        $itemNiveauCompetence = $this->niveauCompetenceService->createInstance();
        

        $competences = $this->competenceService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::niveauCompetence._fields', compact('itemNiveauCompetence', 'competences'));
        }
        return view('PkgCompetences::niveauCompetence.create', compact('itemNiveauCompetence', 'competences'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $niveauCompetence_ids = $request->input('ids', []);

        if (!is_array($niveauCompetence_ids) || count($niveauCompetence_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemNiveauCompetence = $this->niveauCompetenceService->find($niveauCompetence_ids[0]);
         
 
        $competences = $this->competenceService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemNiveauCompetence = $this->niveauCompetenceService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::niveauCompetence._fields', compact('bulkEdit', 'niveauCompetence_ids', 'itemNiveauCompetence', 'competences'));
        }
        return view('PkgCompetences::niveauCompetence.bulk-edit', compact('bulkEdit', 'niveauCompetence_ids', 'itemNiveauCompetence', 'competences'));
    }
    /**
     */
    public function store(NiveauCompetenceRequest $request) {
        $validatedData = $request->validated();
        $niveauCompetence = $this->niveauCompetenceService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' => __('PkgCompetences::niveauCompetence.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $niveauCompetence->id]
            );
        }

        return redirect()->route('niveauCompetences.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' => __('PkgCompetences::niveauCompetence.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('niveauCompetence.edit_' . $id);


        $itemNiveauCompetence = $this->niveauCompetenceService->edit($id);


        $competences = $this->competenceService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::niveauCompetence._fields', array_merge(compact('itemNiveauCompetence','competences'),));
        }

        return view('PkgCompetences::niveauCompetence.edit', array_merge(compact('itemNiveauCompetence','competences'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('niveauCompetence.edit_' . $id);


        $itemNiveauCompetence = $this->niveauCompetenceService->edit($id);


        $competences = $this->competenceService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::niveauCompetence._fields', array_merge(compact('itemNiveauCompetence','competences'),));
        }

        return view('PkgCompetences::niveauCompetence.edit', array_merge(compact('itemNiveauCompetence','competences'),));


    }
    /**
     */
    public function update(NiveauCompetenceRequest $request, string $id) {

        $validatedData = $request->validated();
        $niveauCompetence = $this->niveauCompetenceService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' =>  __('PkgCompetences::niveauCompetence.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $niveauCompetence->id]
            );
        }

        return redirect()->route('niveauCompetences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' =>  __('PkgCompetences::niveauCompetence.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $niveauCompetence_ids = $request->input('niveauCompetence_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($niveauCompetence_ids) || count($niveauCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($niveauCompetence_ids as $id) {
            $entity = $this->niveauCompetenceService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->niveauCompetenceService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->niveauCompetenceService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $niveauCompetence = $this->niveauCompetenceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' =>  __('PkgCompetences::niveauCompetence.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('niveauCompetences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' =>  __('PkgCompetences::niveauCompetence.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $niveauCompetence_ids = $request->input('ids', []);
        if (!is_array($niveauCompetence_ids) || count($niveauCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($niveauCompetence_ids as $id) {
            $entity = $this->niveauCompetenceService->find($id);
            $this->niveauCompetenceService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($niveauCompetence_ids) . ' éléments',
            'modelName' => __('PkgCompetences::niveauCompetence.plural')
        ]));
    }

    public function export($format)
    {
        $niveauCompetences_data = $this->niveauCompetenceService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new NiveauCompetenceExport($niveauCompetences_data,'csv'), 'niveauCompetence_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new NiveauCompetenceExport($niveauCompetences_data,'xlsx'), 'niveauCompetence_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new NiveauCompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('niveauCompetences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('niveauCompetences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::niveauCompetence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNiveauCompetences()
    {
        $niveauCompetences = $this->niveauCompetenceService->all();
        return response()->json($niveauCompetences);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $niveauCompetence = $this->niveauCompetenceService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedNiveauCompetence = $this->niveauCompetenceService->dataCalcul($niveauCompetence);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedNiveauCompetence
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
        $niveauCompetenceRequest = new NiveauCompetenceRequest();
        $fullRules = $niveauCompetenceRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:niveau_competences,id'];
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