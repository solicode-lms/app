<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgApprentissage\Services\EtatRealisationUaService;
use Modules\PkgApprentissage\Services\RealisationMicroCompetenceService;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgApprentissage\Services\RealisationUaPrototypeService;
use Modules\PkgApprentissage\Services\RealisationUaProjetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\RealisationUaRequest;
use Modules\PkgApprentissage\Models\RealisationUa;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\App\Exports\RealisationUaExport;
use Modules\PkgApprentissage\App\Imports\RealisationUaImport;
use Modules\Core\Services\ContextState;

class BaseRealisationUaController extends AdminController
{
    protected $realisationUaService;
    protected $etatRealisationUaService;
    protected $realisationMicroCompetenceService;
    protected $uniteApprentissageService;

    public function __construct(RealisationUaService $realisationUaService, EtatRealisationUaService $etatRealisationUaService, RealisationMicroCompetenceService $realisationMicroCompetenceService, UniteApprentissageService $uniteApprentissageService) {
        parent::__construct();
        $this->service  =  $realisationUaService;
        $this->realisationUaService = $realisationUaService;
        $this->etatRealisationUaService = $etatRealisationUaService;
        $this->realisationMicroCompetenceService = $realisationMicroCompetenceService;
        $this->uniteApprentissageService = $uniteApprentissageService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('realisationUa.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('realisationUa');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $realisationUas_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'realisationUas_search',
                $this->viewState->get("filter.realisationUa.realisationUas_search")
            )],
            $request->except(['realisationUas_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationUaService->prepareDataForIndexView($realisationUas_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::realisationUa._index', $realisationUa_compact_value)->render();
            }else{
                return view($realisationUa_partialViewName, $realisationUa_compact_value)->render();
            }
        }

        return view('PkgApprentissage::realisationUa.index', $realisationUa_compact_value);
    }
    /**
     */
    public function create() {


        $itemRealisationUa = $this->realisationUaService->createInstance();
        

        $uniteApprentissages = $this->uniteApprentissageService->all();
        $realisationMicroCompetences = $this->realisationMicroCompetenceService->all();
        $etatRealisationUas = $this->etatRealisationUaService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUa._fields', compact('bulkEdit' ,'itemRealisationUa', 'etatRealisationUas', 'realisationMicroCompetences', 'uniteApprentissages'));
        }
        return view('PkgApprentissage::realisationUa.create', compact('bulkEdit' ,'itemRealisationUa', 'etatRealisationUas', 'realisationMicroCompetences', 'uniteApprentissages'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $realisationUa_ids = $request->input('ids', []);

        if (!is_array($realisationUa_ids) || count($realisationUa_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemRealisationUa = $this->realisationUaService->find($realisationUa_ids[0]);
         
 
        $uniteApprentissages = $this->uniteApprentissageService->getAllForSelect($itemRealisationUa->uniteApprentissage);
        $realisationMicroCompetences = $this->realisationMicroCompetenceService->getAllForSelect($itemRealisationUa->realisationMicroCompetence);
        $etatRealisationUas = $this->etatRealisationUaService->getAllForSelect($itemRealisationUa->etatRealisationUa);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRealisationUa = $this->realisationUaService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUa._fields', compact('bulkEdit', 'realisationUa_ids', 'itemRealisationUa', 'etatRealisationUas', 'realisationMicroCompetences', 'uniteApprentissages'));
        }
        return view('PkgApprentissage::realisationUa.bulk-edit', compact('bulkEdit', 'realisationUa_ids', 'itemRealisationUa', 'etatRealisationUas', 'realisationMicroCompetences', 'uniteApprentissages'));
    }
    /**
     */
    public function store(RealisationUaRequest $request) {
        $validatedData = $request->validated();
        $realisationUa = $this->realisationUaService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationUa,
                'modelName' => __('PkgApprentissage::realisationUa.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationUa->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('realisationUas.edit', ['realisationUa' => $realisationUa->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationUa,
                'modelName' => __('PkgApprentissage::realisationUa.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('realisationUa.show_' . $id);

        $itemRealisationUa = $this->realisationUaService->edit($id);


        $this->viewState->set('scope.realisationChapitre.realisation_ua_id', $id);
        

        $realisationChapitreService =  new RealisationChapitreService();
        $realisationChapitres_view_data = $realisationChapitreService->prepareDataForIndexView();
        extract($realisationChapitres_view_data);

        $this->viewState->set('scope.realisationUaPrototype.realisation_ua_id', $id);
        

        $realisationUaPrototypeService =  new RealisationUaPrototypeService();
        $realisationUaPrototypes_view_data = $realisationUaPrototypeService->prepareDataForIndexView();
        extract($realisationUaPrototypes_view_data);

        $this->viewState->set('scope.realisationUaProjet.realisation_ua_id', $id);
        

        $realisationUaProjetService =  new RealisationUaProjetService();
        $realisationUaProjets_view_data = $realisationUaProjetService->prepareDataForIndexView();
        extract($realisationUaProjets_view_data);

        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUa._show', array_merge(compact('itemRealisationUa'),$realisationChapitre_compact_value, $realisationUaPrototype_compact_value, $realisationUaProjet_compact_value));
        }

        return view('PkgApprentissage::realisationUa.show', array_merge(compact('itemRealisationUa'),$realisationChapitre_compact_value, $realisationUaPrototype_compact_value, $realisationUaProjet_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationUa.edit_' . $id);


        $itemRealisationUa = $this->realisationUaService->edit($id);


        $uniteApprentissages = $this->uniteApprentissageService->getAllForSelect($itemRealisationUa->uniteApprentissage);
        $realisationMicroCompetences = $this->realisationMicroCompetenceService->getAllForSelect($itemRealisationUa->realisationMicroCompetence);
        $etatRealisationUas = $this->etatRealisationUaService->getAllForSelect($itemRealisationUa->etatRealisationUa);


        $this->viewState->set('scope.realisationChapitre.realisation_ua_id', $id);
        

        $realisationChapitreService =  new RealisationChapitreService();
        $realisationChapitres_view_data = $realisationChapitreService->prepareDataForIndexView();
        extract($realisationChapitres_view_data);

        $this->viewState->set('scope.realisationUaPrototype.realisation_ua_id', $id);
        

        $realisationUaPrototypeService =  new RealisationUaPrototypeService();
        $realisationUaPrototypes_view_data = $realisationUaPrototypeService->prepareDataForIndexView();
        extract($realisationUaPrototypes_view_data);

        $this->viewState->set('scope.realisationUaProjet.realisation_ua_id', $id);
        

        $realisationUaProjetService =  new RealisationUaProjetService();
        $realisationUaProjets_view_data = $realisationUaProjetService->prepareDataForIndexView();
        extract($realisationUaProjets_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::realisationUa._edit', array_merge(compact('bulkEdit' , 'itemRealisationUa','etatRealisationUas', 'realisationMicroCompetences', 'uniteApprentissages'),$realisationChapitre_compact_value, $realisationUaPrototype_compact_value, $realisationUaProjet_compact_value));
        }

        return view('PkgApprentissage::realisationUa.edit', array_merge(compact('bulkEdit' ,'itemRealisationUa','etatRealisationUas', 'realisationMicroCompetences', 'uniteApprentissages'),$realisationChapitre_compact_value, $realisationUaPrototype_compact_value, $realisationUaProjet_compact_value));


    }
    /**
     */
    public function update(RealisationUaRequest $request, string $id) {

        $validatedData = $request->validated();
        $realisationUa = $this->realisationUaService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationUa,
                'modelName' =>  __('PkgApprentissage::realisationUa.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $realisationUa->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('realisationUas.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationUa,
                'modelName' =>  __('PkgApprentissage::realisationUa.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $realisationUa_ids = $request->input('realisationUa_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($realisationUa_ids) || count($realisationUa_ids) === 0) {
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
            $realisationUa_ids,
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

        $realisationUa = $this->realisationUaService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationUa,
                'modelName' =>  __('PkgApprentissage::realisationUa.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('realisationUas.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationUa,
                'modelName' =>  __('PkgApprentissage::realisationUa.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $realisationUa_ids = $request->input('ids', []);
        if (!is_array($realisationUa_ids) || count($realisationUa_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($realisationUa_ids as $id) {
            $entity = $this->realisationUaService->find($id);
            $this->realisationUaService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($realisationUa_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::realisationUa.plural')
        ]));
    }

    public function export($format)
    {
        $realisationUas_data = $this->realisationUaService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationUaExport($realisationUas_data,'csv'), 'realisationUa_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationUaExport($realisationUas_data,'xlsx'), 'realisationUa_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationUaImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationUas.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationUas.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::realisationUa.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationUas()
    {
        $realisationUas = $this->realisationUaService->all();
        return response()->json($realisationUas);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (RealisationUa) par ID, en format JSON.
     */
    public function getRealisationUa(Request $request, $id)
    {
        try {
            $realisationUa = $this->realisationUaService->find($id);
            return response()->json($realisationUa);
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
        $updatedRealisationUa = $this->realisationUaService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedRealisationUa],
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
        $realisationUaRequest = new RealisationUaRequest();
        $fullRules = $realisationUaRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:realisation_uas,id'];
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
        $itemRealisationUa = RealisationUa::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemRealisationUa, $field);
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
        $itemRealisationUa = RealisationUa::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemRealisationUa);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemRealisationUa, $changes);

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