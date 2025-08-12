<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\EtatRealisationCompetenceService;
use Modules\Core\Services\SysColorService;
use Modules\PkgApprentissage\Services\RealisationCompetenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\EtatRealisationCompetenceRequest;
use Modules\PkgApprentissage\Models\EtatRealisationCompetence;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\App\Exports\EtatRealisationCompetenceExport;
use Modules\PkgApprentissage\App\Imports\EtatRealisationCompetenceImport;
use Modules\Core\Services\ContextState;

class BaseEtatRealisationCompetenceController extends AdminController
{
    protected $etatRealisationCompetenceService;
    protected $sysColorService;

    public function __construct(EtatRealisationCompetenceService $etatRealisationCompetenceService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $etatRealisationCompetenceService;
        $this->etatRealisationCompetenceService = $etatRealisationCompetenceService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('etatRealisationCompetence.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('etatRealisationCompetence');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $etatRealisationCompetences_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'etatRealisationCompetences_search',
                $this->viewState->get("filter.etatRealisationCompetence.etatRealisationCompetences_search")
            )],
            $request->except(['etatRealisationCompetences_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatRealisationCompetenceService->prepareDataForIndexView($etatRealisationCompetences_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::etatRealisationCompetence._index', $etatRealisationCompetence_compact_value)->render();
            }else{
                return view($etatRealisationCompetence_partialViewName, $etatRealisationCompetence_compact_value)->render();
            }
        }

        return view('PkgApprentissage::etatRealisationCompetence.index', $etatRealisationCompetence_compact_value);
    }
    /**
     */
    public function create() {


        $itemEtatRealisationCompetence = $this->etatRealisationCompetenceService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationCompetence._fields', compact('bulkEdit' ,'itemEtatRealisationCompetence', 'sysColors'));
        }
        return view('PkgApprentissage::etatRealisationCompetence.create', compact('bulkEdit' ,'itemEtatRealisationCompetence', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $etatRealisationCompetence_ids = $request->input('ids', []);

        if (!is_array($etatRealisationCompetence_ids) || count($etatRealisationCompetence_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEtatRealisationCompetence = $this->etatRealisationCompetenceService->find($etatRealisationCompetence_ids[0]);
         
 
        $sysColors = $this->sysColorService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEtatRealisationCompetence = $this->etatRealisationCompetenceService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationCompetence._fields', compact('bulkEdit', 'etatRealisationCompetence_ids', 'itemEtatRealisationCompetence', 'sysColors'));
        }
        return view('PkgApprentissage::etatRealisationCompetence.bulk-edit', compact('bulkEdit', 'etatRealisationCompetence_ids', 'itemEtatRealisationCompetence', 'sysColors'));
    }
    /**
     */
    public function store(EtatRealisationCompetenceRequest $request) {
        $validatedData = $request->validated();
        $etatRealisationCompetence = $this->etatRealisationCompetenceService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationCompetence,
                'modelName' => __('PkgApprentissage::etatRealisationCompetence.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatRealisationCompetence->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('etatRealisationCompetences.edit', ['etatRealisationCompetence' => $etatRealisationCompetence->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationCompetence,
                'modelName' => __('PkgApprentissage::etatRealisationCompetence.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('etatRealisationCompetence.show_' . $id);

        $itemEtatRealisationCompetence = $this->etatRealisationCompetenceService->edit($id);


        $this->viewState->set('scope.realisationCompetence.etat_realisation_competence_id', $id);
        

        $realisationCompetenceService =  new RealisationCompetenceService();
        $realisationCompetences_view_data = $realisationCompetenceService->prepareDataForIndexView();
        extract($realisationCompetences_view_data);

        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationCompetence._show', array_merge(compact('itemEtatRealisationCompetence'),$realisationCompetence_compact_value));
        }

        return view('PkgApprentissage::etatRealisationCompetence.show', array_merge(compact('itemEtatRealisationCompetence'),$realisationCompetence_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('etatRealisationCompetence.edit_' . $id);


        $itemEtatRealisationCompetence = $this->etatRealisationCompetenceService->edit($id);


        $sysColors = $this->sysColorService->all();


        $this->viewState->set('scope.realisationCompetence.etat_realisation_competence_id', $id);
        

        $realisationCompetenceService =  new RealisationCompetenceService();
        $realisationCompetences_view_data = $realisationCompetenceService->prepareDataForIndexView();
        extract($realisationCompetences_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationCompetence._edit', array_merge(compact('bulkEdit' , 'itemEtatRealisationCompetence','sysColors'),$realisationCompetence_compact_value));
        }

        return view('PkgApprentissage::etatRealisationCompetence.edit', array_merge(compact('bulkEdit' ,'itemEtatRealisationCompetence','sysColors'),$realisationCompetence_compact_value));


    }
    /**
     */
    public function update(EtatRealisationCompetenceRequest $request, string $id) {

        $validatedData = $request->validated();
        $etatRealisationCompetence = $this->etatRealisationCompetenceService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationCompetence,
                'modelName' =>  __('PkgApprentissage::etatRealisationCompetence.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatRealisationCompetence->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('etatRealisationCompetences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationCompetence,
                'modelName' =>  __('PkgApprentissage::etatRealisationCompetence.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $etatRealisationCompetence_ids = $request->input('etatRealisationCompetence_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($etatRealisationCompetence_ids) || count($etatRealisationCompetence_ids) === 0) {
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
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $etatRealisationCompetence_ids,
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

        $etatRealisationCompetence = $this->etatRealisationCompetenceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationCompetence,
                'modelName' =>  __('PkgApprentissage::etatRealisationCompetence.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('etatRealisationCompetences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationCompetence,
                'modelName' =>  __('PkgApprentissage::etatRealisationCompetence.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $etatRealisationCompetence_ids = $request->input('ids', []);
        if (!is_array($etatRealisationCompetence_ids) || count($etatRealisationCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($etatRealisationCompetence_ids as $id) {
            $entity = $this->etatRealisationCompetenceService->find($id);
            $this->etatRealisationCompetenceService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($etatRealisationCompetence_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::etatRealisationCompetence.plural')
        ]));
    }

    public function export($format)
    {
        $etatRealisationCompetences_data = $this->etatRealisationCompetenceService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatRealisationCompetenceExport($etatRealisationCompetences_data,'csv'), 'etatRealisationCompetence_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatRealisationCompetenceExport($etatRealisationCompetences_data,'xlsx'), 'etatRealisationCompetence_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatRealisationCompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatRealisationCompetences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatRealisationCompetences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::etatRealisationCompetence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatRealisationCompetences()
    {
        $etatRealisationCompetences = $this->etatRealisationCompetenceService->all();
        return response()->json($etatRealisationCompetences);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EtatRealisationCompetence) par ID, en format JSON.
     */
    public function getEtatRealisationCompetence(Request $request, $id)
    {
        try {
            $etatRealisationCompetence = $this->etatRealisationCompetenceService->find($id);
            return response()->json($etatRealisationCompetence);
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
        $updatedEtatRealisationCompetence = $this->etatRealisationCompetenceService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedEtatRealisationCompetence],
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
        $etatRealisationCompetenceRequest = new EtatRealisationCompetenceRequest();
        $fullRules = $etatRealisationCompetenceRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:etat_realisation_competences,id'];
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