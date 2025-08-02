<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\MicroCompetenceService;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Modules\PkgApprentissage\Services\RealisationMicroCompetenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\MicroCompetenceRequest;
use Modules\PkgCompetences\Models\MicroCompetence;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\MicroCompetenceExport;
use Modules\PkgCompetences\App\Imports\MicroCompetenceImport;
use Modules\Core\Services\ContextState;

class BaseMicroCompetenceController extends AdminController
{
    protected $microCompetenceService;
    protected $competenceService;

    public function __construct(MicroCompetenceService $microCompetenceService, CompetenceService $competenceService) {
        parent::__construct();
        $this->service  =  $microCompetenceService;
        $this->microCompetenceService = $microCompetenceService;
        $this->competenceService = $competenceService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('microCompetence.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('microCompetence');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $microCompetences_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'microCompetences_search',
                $this->viewState->get("filter.microCompetence.microCompetences_search")
            )],
            $request->except(['microCompetences_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->microCompetenceService->prepareDataForIndexView($microCompetences_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::microCompetence._index', $microCompetence_compact_value)->render();
            }else{
                return view($microCompetence_partialViewName, $microCompetence_compact_value)->render();
            }
        }

        return view('PkgCompetences::microCompetence.index', $microCompetence_compact_value);
    }
    /**
     */
    public function create() {


        $itemMicroCompetence = $this->microCompetenceService->createInstance();
        

        $competences = $this->competenceService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCompetences::microCompetence._fields', compact('bulkEdit' ,'itemMicroCompetence', 'competences'));
        }
        return view('PkgCompetences::microCompetence.create', compact('bulkEdit' ,'itemMicroCompetence', 'competences'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $microCompetence_ids = $request->input('ids', []);

        if (!is_array($microCompetence_ids) || count($microCompetence_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemMicroCompetence = $this->microCompetenceService->find($microCompetence_ids[0]);
         
 
        $competences = $this->competenceService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemMicroCompetence = $this->microCompetenceService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::microCompetence._fields', compact('bulkEdit', 'microCompetence_ids', 'itemMicroCompetence', 'competences'));
        }
        return view('PkgCompetences::microCompetence.bulk-edit', compact('bulkEdit', 'microCompetence_ids', 'itemMicroCompetence', 'competences'));
    }
    /**
     */
    public function store(MicroCompetenceRequest $request) {
        $validatedData = $request->validated();
        $microCompetence = $this->microCompetenceService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $microCompetence,
                'modelName' => __('PkgCompetences::microCompetence.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $microCompetence->id]
            );
        }

        return redirect()->route('microCompetences.edit',['microCompetence' => $microCompetence->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $microCompetence,
                'modelName' => __('PkgCompetences::microCompetence.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('microCompetence.show_' . $id);

        $itemMicroCompetence = $this->microCompetenceService->edit($id);


        $this->viewState->set('scope.uniteApprentissage.micro_competence_id', $id);
        

        $uniteApprentissageService =  new UniteApprentissageService();
        $uniteApprentissages_view_data = $uniteApprentissageService->prepareDataForIndexView();
        extract($uniteApprentissages_view_data);

        if (request()->ajax()) {
            return view('PkgCompetences::microCompetence._show', array_merge(compact('itemMicroCompetence'),$uniteApprentissage_compact_value));
        }

        return view('PkgCompetences::microCompetence.show', array_merge(compact('itemMicroCompetence'),$uniteApprentissage_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('microCompetence.edit_' . $id);


        $itemMicroCompetence = $this->microCompetenceService->edit($id);


        $competences = $this->competenceService->all();


        $this->viewState->set('scope.uniteApprentissage.micro_competence_id', $id);
        

        $uniteApprentissageService =  new UniteApprentissageService();
        $uniteApprentissages_view_data = $uniteApprentissageService->prepareDataForIndexView();
        extract($uniteApprentissages_view_data);

        $this->viewState->set('scope.realisationMicroCompetence.micro_competence_id', $id);
        

        $realisationMicroCompetenceService =  new RealisationMicroCompetenceService();
        $realisationMicroCompetences_view_data = $realisationMicroCompetenceService->prepareDataForIndexView();
        extract($realisationMicroCompetences_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCompetences::microCompetence._edit', array_merge(compact('bulkEdit' , 'itemMicroCompetence','competences'),$uniteApprentissage_compact_value, $realisationMicroCompetence_compact_value));
        }

        return view('PkgCompetences::microCompetence.edit', array_merge(compact('bulkEdit' ,'itemMicroCompetence','competences'),$uniteApprentissage_compact_value, $realisationMicroCompetence_compact_value));


    }
    /**
     */
    public function update(MicroCompetenceRequest $request, string $id) {

        $validatedData = $request->validated();
        $microCompetence = $this->microCompetenceService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $microCompetence,
                'modelName' =>  __('PkgCompetences::microCompetence.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $microCompetence->id]
            );
        }

        return redirect()->route('microCompetences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $microCompetence,
                'modelName' =>  __('PkgCompetences::microCompetence.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $microCompetence_ids = $request->input('microCompetence_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($microCompetence_ids) || count($microCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($microCompetence_ids as $id) {
            $entity = $this->microCompetenceService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->microCompetenceService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->microCompetenceService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $microCompetence = $this->microCompetenceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $microCompetence,
                'modelName' =>  __('PkgCompetences::microCompetence.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('microCompetences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $microCompetence,
                'modelName' =>  __('PkgCompetences::microCompetence.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $microCompetence_ids = $request->input('ids', []);
        if (!is_array($microCompetence_ids) || count($microCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($microCompetence_ids as $id) {
            $entity = $this->microCompetenceService->find($id);
            $this->microCompetenceService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($microCompetence_ids) . ' éléments',
            'modelName' => __('PkgCompetences::microCompetence.plural')
        ]));
    }

    public function export($format)
    {
        $microCompetences_data = $this->microCompetenceService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new MicroCompetenceExport($microCompetences_data,'csv'), 'microCompetence_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new MicroCompetenceExport($microCompetences_data,'xlsx'), 'microCompetence_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new MicroCompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('microCompetences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('microCompetences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::microCompetence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getMicroCompetences()
    {
        $microCompetences = $this->microCompetenceService->all();
        return response()->json($microCompetences);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $microCompetence = $this->microCompetenceService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedMicroCompetence = $this->microCompetenceService->dataCalcul($microCompetence);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedMicroCompetence
        ]);
    }
    
    public function startFormation(Request $request, string $id) {
        $microCompetence = $this->microCompetenceService->startFormation($id);
        if ($request->ajax()) {
            $message = "La formation a été lancée avec succès";
            return JsonResponseHelper::success(
                $message
            );
        }
        return redirect()->route('MicroCompetence.index')->with(
            'success',
            "La formation a été lancée avec succès"
        );
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
        $microCompetenceRequest = new MicroCompetenceRequest();
        $fullRules = $microCompetenceRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:micro_competences,id'];
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