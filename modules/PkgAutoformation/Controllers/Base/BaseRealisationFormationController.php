<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Controllers\Base;
use Modules\PkgAutoformation\Services\RealisationFormationService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgAutoformation\Services\EtatFormationService;
use Modules\PkgAutoformation\Services\FormationService;
use Modules\PkgAutoformation\Services\RealisationChapitreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutoformation\App\Requests\RealisationFormationRequest;
use Modules\PkgAutoformation\Models\RealisationFormation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutoformation\App\Exports\RealisationFormationExport;
use Modules\PkgAutoformation\App\Imports\RealisationFormationImport;
use Modules\Core\Services\ContextState;

class BaseRealisationFormationController extends AdminController
{
    protected $realisationFormationService;
    protected $apprenantService;
    protected $etatFormationService;
    protected $formationService;

    public function __construct(RealisationFormationService $realisationFormationService, ApprenantService $apprenantService, EtatFormationService $etatFormationService, FormationService $formationService) {
        parent::__construct();
        $this->service  =  $realisationFormationService;
        $this->realisationFormationService = $realisationFormationService;
        $this->apprenantService = $apprenantService;
        $this->etatFormationService = $etatFormationService;
        $this->formationService = $formationService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('realisationFormation.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('realisationFormation');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $realisationFormations_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'realisationFormations_search',
                $this->viewState->get("filter.realisationFormation.realisationFormations_search")
            )],
            $request->except(['realisationFormations_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationFormationService->prepareDataForIndexView($realisationFormations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgAutoformation::realisationFormation._index', $realisationFormation_compact_value)->render();
            }else{
                return view($realisationFormation_partialViewName, $realisationFormation_compact_value)->render();
            }
        }

        return view('PkgAutoformation::realisationFormation.index', $realisationFormation_compact_value);
    }
    /**
     */
    public function create() {


        $itemRealisationFormation = $this->realisationFormationService->createInstance();
        

        $formations = $this->formationService->all();
        $apprenants = $this->apprenantService->all();
        $etatFormations = $this->etatFormationService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgAutoformation::realisationFormation._fields', compact('bulkEdit' ,'itemRealisationFormation', 'apprenants', 'etatFormations', 'formations'));
        }
        return view('PkgAutoformation::realisationFormation.create', compact('bulkEdit' ,'itemRealisationFormation', 'apprenants', 'etatFormations', 'formations'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $realisationFormation_ids = $request->input('ids', []);

        if (!is_array($realisationFormation_ids) || count($realisationFormation_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemRealisationFormation = $this->realisationFormationService->find($realisationFormation_ids[0]);
         
 
        $formations = $this->formationService->all();
        $apprenants = $this->apprenantService->all();
        $etatFormations = $this->etatFormationService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRealisationFormation = $this->realisationFormationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgAutoformation::realisationFormation._fields', compact('bulkEdit', 'realisationFormation_ids', 'itemRealisationFormation', 'apprenants', 'etatFormations', 'formations'));
        }
        return view('PkgAutoformation::realisationFormation.bulk-edit', compact('bulkEdit', 'realisationFormation_ids', 'itemRealisationFormation', 'apprenants', 'etatFormations', 'formations'));
    }
    /**
     */
    public function store(RealisationFormationRequest $request) {
        $validatedData = $request->validated();
        $realisationFormation = $this->realisationFormationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationFormation,
                'modelName' => __('PkgAutoformation::realisationFormation.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $realisationFormation->id]
            );
        }

        return redirect()->route('realisationFormations.edit',['realisationFormation' => $realisationFormation->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationFormation,
                'modelName' => __('PkgAutoformation::realisationFormation.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('realisationFormation.show_' . $id);

        $itemRealisationFormation = $this->realisationFormationService->edit($id);


        $this->viewState->set('scope.realisationChapitre.realisation_formation_id', $id);
        

        $realisationChapitreService =  new RealisationChapitreService();
        $realisationChapitres_view_data = $realisationChapitreService->prepareDataForIndexView();
        extract($realisationChapitres_view_data);

        if (request()->ajax()) {
            return view('PkgAutoformation::realisationFormation._show', array_merge(compact('itemRealisationFormation'),$realisationChapitre_compact_value));
        }

        return view('PkgAutoformation::realisationFormation.show', array_merge(compact('itemRealisationFormation'),$realisationChapitre_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationFormation.edit_' . $id);


        $itemRealisationFormation = $this->realisationFormationService->edit($id);


        $formations = $this->formationService->all();
        $apprenants = $this->apprenantService->all();
        $etatFormations = $this->etatFormationService->all();


        $this->viewState->set('scope.realisationChapitre.realisation_formation_id', $id);
        

        $realisationChapitreService =  new RealisationChapitreService();
        $realisationChapitres_view_data = $realisationChapitreService->prepareDataForIndexView();
        extract($realisationChapitres_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgAutoformation::realisationFormation._edit', array_merge(compact('bulkEdit' , 'itemRealisationFormation','apprenants', 'etatFormations', 'formations'),$realisationChapitre_compact_value));
        }

        return view('PkgAutoformation::realisationFormation.edit', array_merge(compact('bulkEdit' ,'itemRealisationFormation','apprenants', 'etatFormations', 'formations'),$realisationChapitre_compact_value));


    }
    /**
     */
    public function update(RealisationFormationRequest $request, string $id) {

        $validatedData = $request->validated();
        $realisationFormation = $this->realisationFormationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationFormation,
                'modelName' =>  __('PkgAutoformation::realisationFormation.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $realisationFormation->id]
            );
        }

        return redirect()->route('realisationFormations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationFormation,
                'modelName' =>  __('PkgAutoformation::realisationFormation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $realisationFormation_ids = $request->input('realisationFormation_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($realisationFormation_ids) || count($realisationFormation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($realisationFormation_ids as $id) {
            $entity = $this->realisationFormationService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->realisationFormationService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->realisationFormationService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $realisationFormation = $this->realisationFormationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationFormation,
                'modelName' =>  __('PkgAutoformation::realisationFormation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('realisationFormations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationFormation,
                'modelName' =>  __('PkgAutoformation::realisationFormation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $realisationFormation_ids = $request->input('ids', []);
        if (!is_array($realisationFormation_ids) || count($realisationFormation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($realisationFormation_ids as $id) {
            $entity = $this->realisationFormationService->find($id);
            $this->realisationFormationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($realisationFormation_ids) . ' éléments',
            'modelName' => __('PkgAutoformation::realisationFormation.plural')
        ]));
    }

    public function export($format)
    {
        $realisationFormations_data = $this->realisationFormationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationFormationExport($realisationFormations_data,'csv'), 'realisationFormation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationFormationExport($realisationFormations_data,'xlsx'), 'realisationFormation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationFormationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationFormations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationFormations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutoformation::realisationFormation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationFormations()
    {
        $realisationFormations = $this->realisationFormationService->all();
        return response()->json($realisationFormations);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $realisationFormation = $this->realisationFormationService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedRealisationFormation = $this->realisationFormationService->dataCalcul($realisationFormation);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedRealisationFormation
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
        $realisationFormationRequest = new RealisationFormationRequest();
        $fullRules = $realisationFormationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:realisation_formations,id'];
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