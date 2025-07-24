<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\RealisationMicroCompetenceService;
use Modules\PkgApprentissage\Services\EtatRealisationMicroCompetenceService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgCompetences\Services\MicroCompetenceService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\RealisationMicroCompetenceRequest;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprentissage\App\Exports\RealisationMicroCompetenceExport;
use Modules\PkgApprentissage\App\Imports\RealisationMicroCompetenceImport;
use Modules\Core\Services\ContextState;

class BaseRealisationMicroCompetenceController extends AdminController
{
    protected $realisationMicroCompetenceService;
    protected $etatRealisationMicroCompetenceService;
    protected $apprenantService;
    protected $microCompetenceService;

    public function __construct(RealisationMicroCompetenceService $realisationMicroCompetenceService, EtatRealisationMicroCompetenceService $etatRealisationMicroCompetenceService, ApprenantService $apprenantService, MicroCompetenceService $microCompetenceService) {
        parent::__construct();
        $this->service  =  $realisationMicroCompetenceService;
        $this->realisationMicroCompetenceService = $realisationMicroCompetenceService;
        $this->etatRealisationMicroCompetenceService = $etatRealisationMicroCompetenceService;
        $this->apprenantService = $apprenantService;
        $this->microCompetenceService = $microCompetenceService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('realisationMicroCompetence.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('realisationMicroCompetence');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $realisationMicroCompetences_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'realisationMicroCompetences_search',
                $this->viewState->get("filter.realisationMicroCompetence.realisationMicroCompetences_search")
            )],
            $request->except(['realisationMicroCompetences_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationMicroCompetenceService->prepareDataForIndexView($realisationMicroCompetences_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::realisationMicroCompetence._index', $realisationMicroCompetence_compact_value)->render();
            }else{
                return view($realisationMicroCompetence_partialViewName, $realisationMicroCompetence_compact_value)->render();
            }
        }

        return view('PkgApprentissage::realisationMicroCompetence.index', $realisationMicroCompetence_compact_value);
    }
    /**
     */
    public function create() {


        $itemRealisationMicroCompetence = $this->realisationMicroCompetenceService->createInstance();
        

        $apprenants = $this->apprenantService->all();
        $microCompetences = $this->microCompetenceService->all();
        $etatRealisationMicroCompetences = $this->etatRealisationMicroCompetenceService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationMicroCompetence._fields', compact('bulkEdit' ,'itemRealisationMicroCompetence', 'etatRealisationMicroCompetences', 'apprenants', 'microCompetences'));
        }
        return view('PkgApprentissage::realisationMicroCompetence.create', compact('bulkEdit' ,'itemRealisationMicroCompetence', 'etatRealisationMicroCompetences', 'apprenants', 'microCompetences'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $realisationMicroCompetence_ids = $request->input('ids', []);

        if (!is_array($realisationMicroCompetence_ids) || count($realisationMicroCompetence_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemRealisationMicroCompetence = $this->realisationMicroCompetenceService->find($realisationMicroCompetence_ids[0]);
         
 
        $apprenants = $this->apprenantService->all();
        $microCompetences = $this->microCompetenceService->all();
        $etatRealisationMicroCompetences = $this->etatRealisationMicroCompetenceService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRealisationMicroCompetence = $this->realisationMicroCompetenceService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationMicroCompetence._fields', compact('bulkEdit', 'realisationMicroCompetence_ids', 'itemRealisationMicroCompetence', 'etatRealisationMicroCompetences', 'apprenants', 'microCompetences'));
        }
        return view('PkgApprentissage::realisationMicroCompetence.bulk-edit', compact('bulkEdit', 'realisationMicroCompetence_ids', 'itemRealisationMicroCompetence', 'etatRealisationMicroCompetences', 'apprenants', 'microCompetences'));
    }
    /**
     */
    public function store(RealisationMicroCompetenceRequest $request) {
        $validatedData = $request->validated();
        $realisationMicroCompetence = $this->realisationMicroCompetenceService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationMicroCompetence,
                'modelName' => __('PkgApprentissage::realisationMicroCompetence.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $realisationMicroCompetence->id]
            );
        }

        return redirect()->route('realisationMicroCompetences.edit',['realisationMicroCompetence' => $realisationMicroCompetence->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationMicroCompetence,
                'modelName' => __('PkgApprentissage::realisationMicroCompetence.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('realisationMicroCompetence.show_' . $id);

        $itemRealisationMicroCompetence = $this->realisationMicroCompetenceService->edit($id);


        $this->viewState->set('scope.realisationUa.realisation_micro_competence_id', $id);
        

        $realisationUaService =  new RealisationUaService();
        $realisationUas_view_data = $realisationUaService->prepareDataForIndexView();
        extract($realisationUas_view_data);

        if (request()->ajax()) {
            return view('PkgApprentissage::realisationMicroCompetence._show', array_merge(compact('itemRealisationMicroCompetence'),$realisationUa_compact_value));
        }

        return view('PkgApprentissage::realisationMicroCompetence.show', array_merge(compact('itemRealisationMicroCompetence'),$realisationUa_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationMicroCompetence.edit_' . $id);


        $itemRealisationMicroCompetence = $this->realisationMicroCompetenceService->edit($id);


        $apprenants = $this->apprenantService->all();
        $microCompetences = $this->microCompetenceService->all();
        $etatRealisationMicroCompetences = $this->etatRealisationMicroCompetenceService->all();


        $this->viewState->set('scope.realisationUa.realisation_micro_competence_id', $id);
        

        $realisationUaService =  new RealisationUaService();
        $realisationUas_view_data = $realisationUaService->prepareDataForIndexView();
        extract($realisationUas_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::realisationMicroCompetence._edit', array_merge(compact('bulkEdit' , 'itemRealisationMicroCompetence','etatRealisationMicroCompetences', 'apprenants', 'microCompetences'),$realisationUa_compact_value));
        }

        return view('PkgApprentissage::realisationMicroCompetence.edit', array_merge(compact('bulkEdit' ,'itemRealisationMicroCompetence','etatRealisationMicroCompetences', 'apprenants', 'microCompetences'),$realisationUa_compact_value));


    }
    /**
     */
    public function update(RealisationMicroCompetenceRequest $request, string $id) {

        $validatedData = $request->validated();
        $realisationMicroCompetence = $this->realisationMicroCompetenceService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationMicroCompetence,
                'modelName' =>  __('PkgApprentissage::realisationMicroCompetence.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $realisationMicroCompetence->id]
            );
        }

        return redirect()->route('realisationMicroCompetences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationMicroCompetence,
                'modelName' =>  __('PkgApprentissage::realisationMicroCompetence.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $realisationMicroCompetence_ids = $request->input('realisationMicroCompetence_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($realisationMicroCompetence_ids) || count($realisationMicroCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($realisationMicroCompetence_ids as $id) {
            $entity = $this->realisationMicroCompetenceService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->realisationMicroCompetenceService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->realisationMicroCompetenceService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $realisationMicroCompetence = $this->realisationMicroCompetenceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationMicroCompetence,
                'modelName' =>  __('PkgApprentissage::realisationMicroCompetence.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('realisationMicroCompetences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationMicroCompetence,
                'modelName' =>  __('PkgApprentissage::realisationMicroCompetence.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $realisationMicroCompetence_ids = $request->input('ids', []);
        if (!is_array($realisationMicroCompetence_ids) || count($realisationMicroCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($realisationMicroCompetence_ids as $id) {
            $entity = $this->realisationMicroCompetenceService->find($id);
            $this->realisationMicroCompetenceService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($realisationMicroCompetence_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::realisationMicroCompetence.plural')
        ]));
    }

    public function export($format)
    {
        $realisationMicroCompetences_data = $this->realisationMicroCompetenceService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationMicroCompetenceExport($realisationMicroCompetences_data,'csv'), 'realisationMicroCompetence_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationMicroCompetenceExport($realisationMicroCompetences_data,'xlsx'), 'realisationMicroCompetence_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationMicroCompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationMicroCompetences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationMicroCompetences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::realisationMicroCompetence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationMicroCompetences()
    {
        $realisationMicroCompetences = $this->realisationMicroCompetenceService->all();
        return response()->json($realisationMicroCompetences);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $realisationMicroCompetence = $this->realisationMicroCompetenceService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedRealisationMicroCompetence = $this->realisationMicroCompetenceService->dataCalcul($realisationMicroCompetence);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedRealisationMicroCompetence
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
        $realisationMicroCompetenceRequest = new RealisationMicroCompetenceRequest();
        $fullRules = $realisationMicroCompetenceRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:realisation_micro_competences,id'];
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