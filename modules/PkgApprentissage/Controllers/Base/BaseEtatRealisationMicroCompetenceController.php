<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\EtatRealisationMicroCompetenceService;
use Modules\Core\Services\SysColorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\EtatRealisationMicroCompetenceRequest;
use Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
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
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
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
         
 
        $sysColors = $this->sysColorService->getAllForSelect($itemEtatRealisationMicroCompetence->sysColor);

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
                array_merge(
                    ['entity_id' => $etatRealisationMicroCompetence->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('etatRealisationMicroCompetences.index')->with(
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


        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationMicroCompetence._show', array_merge(compact('itemEtatRealisationMicroCompetence'),));
        }

        return view('PkgApprentissage::etatRealisationMicroCompetence.show', array_merge(compact('itemEtatRealisationMicroCompetence'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('etatRealisationMicroCompetence.edit_' . $id);


        $itemEtatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->edit($id);


        $sysColors = $this->sysColorService->getAllForSelect($itemEtatRealisationMicroCompetence->sysColor);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationMicroCompetence._fields', array_merge(compact('bulkEdit' , 'itemEtatRealisationMicroCompetence','sysColors'),));
        }

        return view('PkgApprentissage::etatRealisationMicroCompetence.edit', array_merge(compact('bulkEdit' ,'itemEtatRealisationMicroCompetence','sysColors'),));


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
                array_merge(
                    ['entity_id' => $etatRealisationMicroCompetence->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
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

        // 🔹 Récupérer les valeurs de ces champs
        $valeursChamps = [];
        foreach ($champsCoches as $field) {
            $valeursChamps[$field] = $request->input($field);
        }

        $jobManager = new JobManager();
        $jobManager->init("bulkUpdateJob",$this->service->modelName,$this->service->moduleName);
         
        dispatch(new BulkEditJob(
            Auth::id(),
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $etatRealisationMicroCompetence_ids,
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

        $etatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationMicroCompetence,
                'modelName' =>  __('PkgApprentissage::etatRealisationMicroCompetence.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
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

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EtatRealisationMicroCompetence) par ID, en format JSON.
     */
    public function getEtatRealisationMicroCompetence(Request $request, $id)
    {
        try {
            $etatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->find($id);
            return response()->json($etatRealisationMicroCompetence);
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
        $updatedEtatRealisationMicroCompetence = $this->etatRealisationMicroCompetenceService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedEtatRealisationMicroCompetence],
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
    
        return JsonResponseHelper::success(
             __('Mise à jour réussie.'),
                array_merge(
                    ['entity_id' => $validated['id']],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
        );
    }
}