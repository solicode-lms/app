<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\EtatRealisationUaService;
use Modules\Core\Services\SysColorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\EtatRealisationUaRequest;
use Modules\PkgApprentissage\Models\EtatRealisationUa;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\App\Exports\EtatRealisationUaExport;
use Modules\PkgApprentissage\App\Imports\EtatRealisationUaImport;
use Modules\Core\Services\ContextState;

class BaseEtatRealisationUaController extends AdminController
{
    protected $etatRealisationUaService;
    protected $sysColorService;

    public function __construct(EtatRealisationUaService $etatRealisationUaService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $etatRealisationUaService;
        $this->etatRealisationUaService = $etatRealisationUaService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('etatRealisationUa.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('etatRealisationUa');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $etatRealisationUas_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'etatRealisationUas_search',
                $this->viewState->get("filter.etatRealisationUa.etatRealisationUas_search")
            )],
            $request->except(['etatRealisationUas_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatRealisationUaService->prepareDataForIndexView($etatRealisationUas_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::etatRealisationUa._index', $etatRealisationUa_compact_value)->render();
            }else{
                return view($etatRealisationUa_partialViewName, $etatRealisationUa_compact_value)->render();
            }
        }

        return view('PkgApprentissage::etatRealisationUa.index', $etatRealisationUa_compact_value);
    }
    /**
     */
    public function create() {


        $itemEtatRealisationUa = $this->etatRealisationUaService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationUa._fields', compact('bulkEdit' ,'itemEtatRealisationUa', 'sysColors'));
        }
        return view('PkgApprentissage::etatRealisationUa.create', compact('bulkEdit' ,'itemEtatRealisationUa', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $etatRealisationUa_ids = $request->input('ids', []);

        if (!is_array($etatRealisationUa_ids) || count($etatRealisationUa_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEtatRealisationUa = $this->etatRealisationUaService->find($etatRealisationUa_ids[0]);
         
 
        $sysColors = $this->sysColorService->getAllForSelect($itemEtatRealisationUa->sysColor);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEtatRealisationUa = $this->etatRealisationUaService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationUa._fields', compact('bulkEdit', 'etatRealisationUa_ids', 'itemEtatRealisationUa', 'sysColors'));
        }
        return view('PkgApprentissage::etatRealisationUa.bulk-edit', compact('bulkEdit', 'etatRealisationUa_ids', 'itemEtatRealisationUa', 'sysColors'));
    }
    /**
     */
    public function store(EtatRealisationUaRequest $request) {
        $validatedData = $request->validated();
        $etatRealisationUa = $this->etatRealisationUaService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationUa,
                'modelName' => __('PkgApprentissage::etatRealisationUa.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatRealisationUa->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('etatRealisationUas.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationUa,
                'modelName' => __('PkgApprentissage::etatRealisationUa.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('etatRealisationUa.show_' . $id);

        $itemEtatRealisationUa = $this->etatRealisationUaService->edit($id);


        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationUa._show', array_merge(compact('itemEtatRealisationUa'),));
        }

        return view('PkgApprentissage::etatRealisationUa.show', array_merge(compact('itemEtatRealisationUa'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('etatRealisationUa.edit_' . $id);


        $itemEtatRealisationUa = $this->etatRealisationUaService->edit($id);


        $sysColors = $this->sysColorService->getAllForSelect($itemEtatRealisationUa->sysColor);


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationUa._fields', array_merge(compact('bulkEdit' , 'itemEtatRealisationUa','sysColors'),));
        }

        return view('PkgApprentissage::etatRealisationUa.edit', array_merge(compact('bulkEdit' ,'itemEtatRealisationUa','sysColors'),));


    }
    /**
     */
    public function update(EtatRealisationUaRequest $request, string $id) {

        $validatedData = $request->validated();
        $etatRealisationUa = $this->etatRealisationUaService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationUa,
                'modelName' =>  __('PkgApprentissage::etatRealisationUa.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatRealisationUa->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('etatRealisationUas.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationUa,
                'modelName' =>  __('PkgApprentissage::etatRealisationUa.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $etatRealisationUa_ids = $request->input('etatRealisationUa_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($etatRealisationUa_ids) || count($etatRealisationUa_ids) === 0) {
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
            $etatRealisationUa_ids,
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

        $etatRealisationUa = $this->etatRealisationUaService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationUa,
                'modelName' =>  __('PkgApprentissage::etatRealisationUa.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('etatRealisationUas.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationUa,
                'modelName' =>  __('PkgApprentissage::etatRealisationUa.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $etatRealisationUa_ids = $request->input('ids', []);
        if (!is_array($etatRealisationUa_ids) || count($etatRealisationUa_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($etatRealisationUa_ids as $id) {
            $entity = $this->etatRealisationUaService->find($id);
            $this->etatRealisationUaService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($etatRealisationUa_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::etatRealisationUa.plural')
        ]));
    }

    public function export($format)
    {
        $etatRealisationUas_data = $this->etatRealisationUaService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatRealisationUaExport($etatRealisationUas_data,'csv'), 'etatRealisationUa_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatRealisationUaExport($etatRealisationUas_data,'xlsx'), 'etatRealisationUa_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatRealisationUaImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatRealisationUas.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatRealisationUas.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::etatRealisationUa.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatRealisationUas()
    {
        $etatRealisationUas = $this->etatRealisationUaService->all();
        return response()->json($etatRealisationUas);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EtatRealisationUa) par ID, en format JSON.
     */
    public function getEtatRealisationUa(Request $request, $id)
    {
        try {
            $etatRealisationUa = $this->etatRealisationUaService->find($id);
            return response()->json($etatRealisationUa);
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
        $updatedEtatRealisationUa = $this->etatRealisationUaService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedEtatRealisationUa],
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
        $etatRealisationUaRequest = new EtatRealisationUaRequest();
        $fullRules = $etatRealisationUaRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:etat_realisation_uas,id'];
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

    /**
     * Retourne les métadonnées d’un champ (type, options, validation, etag…)
     *  @DynamicPermissionIgnore
     */
    public function fieldMeta(int $id, string $field)
    {
        // $this->authorizeAction('update');
        $itemEtatRealisationUa = EtatRealisationUa::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemEtatRealisationUa, $field);
        return response()->json(
            $data
        );
    }

    /**
     * PATCH inline d’une cellule avec gestion de l’ETag
     * @DynamicPermissionIgnore
     */
    public function patchInline(Request $request, int $id)
    {

        $this->authorizeAction('update');
        $itemEtatRealisationUa = EtatRealisationUa::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemEtatRealisationUa);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemEtatRealisationUa, $changes);

        return response()->json(
            array_merge(
                [
                    "ok"        => true,
                    "entity_id" => $updated->id,
                    "display"   => $this->service->formatDisplayValues($updated, array_keys($changes)),
                    "etag"      => $this->service->etag($updated),
                ],
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            )
        );
    }

   
}