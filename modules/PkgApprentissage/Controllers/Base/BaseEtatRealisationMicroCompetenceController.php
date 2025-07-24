<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\EtatRealisationMicroCompetenceService;
use Modules\Core\Services\SysColorService;
use Modules\PkgApprentissage\Services\RealisationMicroCompetenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\EtatRealisationMicroCompetenceRequest;
use Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprentissage\App\Exports\EtatRealisationMicroCompetenceExport;
use Modules\PkgApprentissage\App\Imports\EtatRealisationMicroCompetenceImport;
use Modules\Core\Services\ContextState;

class BaseEtatRealisationMicroCompetenceController extends AdminController
{
    protected $etatRealisationMicroCompetenceService;
    protected $sysColorService;

    public function __construct(EtatRealisationMicroCompetenceService $etatRealisationMicroCompetenceService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $etatRealisationMicroCompetenceService;
        $this->etatRealisationMicroCompetenceService = $etatRealisationMicroCompetenceService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('etatRealisationMicroCompetence.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('etatRealisationMicroCompetence');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $etatRealisationMicroCompetences_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'etatRealisationMicroCompetences_search',
                $this->viewState->get("filter.etatRealisationMicroCompetence.etatRealisationMicroCompetences_search")
            )],
            $request->except(['etatRealisationMicroCompetences_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatRealisationMicroCompetenceService->prepareDataForIndexView($etatRealisationMicroCompetences_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::etatRealisationMicroCompetence._index', $etatRealisationMicroCompetence_compact_value)->render();
            }else{
                return view($etatRealisationMicroCompetence_partialViewName, $etatRealisationMicroCompetence_compact_value)->render();
            }
        }

        return view('PkgApprentissage::etatRealisationMicroCompetence.index', $etatRealisationMicroCompetence_compact_value);
    }
    /**
     */
    public function create() {


        $itemEtatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationMicroCompetence._fields', compact('bulkEdit' ,'itemEtatRealisationMicroCompetence', 'sysColors'));
        }
        return view('PkgApprentissage::etatRealisationMicroCompetence.create', compact('bulkEdit' ,'itemEtatRealisationMicroCompetence', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $etatRealisationMicroCompetence_ids = $request->input('ids', []);

        if (!is_array($etatRealisationMicroCompetence_ids) || count($etatRealisationMicroCompetence_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEtatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->find($etatRealisationMicroCompetence_ids[0]);
         
 
        $sysColors = $this->sysColorService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEtatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationMicroCompetence._fields', compact('bulkEdit', 'etatRealisationMicroCompetence_ids', 'itemEtatRealisationMicroCompetence', 'sysColors'));
        }
        return view('PkgApprentissage::etatRealisationMicroCompetence.bulk-edit', compact('bulkEdit', 'etatRealisationMicroCompetence_ids', 'itemEtatRealisationMicroCompetence', 'sysColors'));
    }
    /**
     */
    public function store(EtatRealisationMicroCompetenceRequest $request) {
        $validatedData = $request->validated();
        $etatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationMicroCompetence,
                'modelName' => __('PkgApprentissage::etatRealisationMicroCompetence.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $etatRealisationMicroCompetence->id]
            );
        }

        return redirect()->route('etatRealisationMicroCompetences.edit',['etatRealisationMicroCompetence' => $etatRealisationMicroCompetence->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationMicroCompetence,
                'modelName' => __('PkgApprentissage::etatRealisationMicroCompetence.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('etatRealisationMicroCompetence.show_' . $id);

        $itemEtatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->edit($id);


        $this->viewState->set('scope.realisationMicroCompetence.etat_realisation_micro_competence_id', $id);
        

        $realisationMicroCompetenceService =  new RealisationMicroCompetenceService();
        $realisationMicroCompetences_view_data = $realisationMicroCompetenceService->prepareDataForIndexView();
        extract($realisationMicroCompetences_view_data);

        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationMicroCompetence._show', array_merge(compact('itemEtatRealisationMicroCompetence'),$realisationMicroCompetence_compact_value));
        }

        return view('PkgApprentissage::etatRealisationMicroCompetence.show', array_merge(compact('itemEtatRealisationMicroCompetence'),$realisationMicroCompetence_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('etatRealisationMicroCompetence.edit_' . $id);


        $itemEtatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->edit($id);


        $sysColors = $this->sysColorService->all();


        $this->viewState->set('scope.realisationMicroCompetence.etat_realisation_micro_competence_id', $id);
        

        $realisationMicroCompetenceService =  new RealisationMicroCompetenceService();
        $realisationMicroCompetences_view_data = $realisationMicroCompetenceService->prepareDataForIndexView();
        extract($realisationMicroCompetences_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationMicroCompetence._edit', array_merge(compact('bulkEdit' , 'itemEtatRealisationMicroCompetence','sysColors'),$realisationMicroCompetence_compact_value));
        }

        return view('PkgApprentissage::etatRealisationMicroCompetence.edit', array_merge(compact('bulkEdit' ,'itemEtatRealisationMicroCompetence','sysColors'),$realisationMicroCompetence_compact_value));


    }
    /**
     */
    public function update(EtatRealisationMicroCompetenceRequest $request, string $id) {

        $validatedData = $request->validated();
        $etatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationMicroCompetence,
                'modelName' =>  __('PkgApprentissage::etatRealisationMicroCompetence.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $etatRealisationMicroCompetence->id]
            );
        }

        return redirect()->route('etatRealisationMicroCompetences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationMicroCompetence,
                'modelName' =>  __('PkgApprentissage::etatRealisationMicroCompetence.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $etatRealisationMicroCompetence_ids = $request->input('etatRealisationMicroCompetence_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($etatRealisationMicroCompetence_ids) || count($etatRealisationMicroCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($etatRealisationMicroCompetence_ids as $id) {
            $entity = $this->etatRealisationMicroCompetenceService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->etatRealisationMicroCompetenceService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->etatRealisationMicroCompetenceService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $etatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationMicroCompetence,
                'modelName' =>  __('PkgApprentissage::etatRealisationMicroCompetence.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('etatRealisationMicroCompetences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationMicroCompetence,
                'modelName' =>  __('PkgApprentissage::etatRealisationMicroCompetence.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $etatRealisationMicroCompetence_ids = $request->input('ids', []);
        if (!is_array($etatRealisationMicroCompetence_ids) || count($etatRealisationMicroCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($etatRealisationMicroCompetence_ids as $id) {
            $entity = $this->etatRealisationMicroCompetenceService->find($id);
            $this->etatRealisationMicroCompetenceService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($etatRealisationMicroCompetence_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::etatRealisationMicroCompetence.plural')
        ]));
    }

    public function export($format)
    {
        $etatRealisationMicroCompetences_data = $this->etatRealisationMicroCompetenceService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatRealisationMicroCompetenceExport($etatRealisationMicroCompetences_data,'csv'), 'etatRealisationMicroCompetence_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatRealisationMicroCompetenceExport($etatRealisationMicroCompetences_data,'xlsx'), 'etatRealisationMicroCompetence_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatRealisationMicroCompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatRealisationMicroCompetences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatRealisationMicroCompetences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::etatRealisationMicroCompetence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatRealisationMicroCompetences()
    {
        $etatRealisationMicroCompetences = $this->etatRealisationMicroCompetenceService->all();
        return response()->json($etatRealisationMicroCompetences);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $etatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEtatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->dataCalcul($etatRealisationMicroCompetence);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedEtatRealisationMicroCompetence
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
        $etatRealisationMicroCompetenceRequest = new EtatRealisationMicroCompetenceRequest();
        $fullRules = $etatRealisationMicroCompetenceRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:etat_realisation_micro_competences,id'];
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