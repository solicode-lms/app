<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\EtatRealisationChapitreService;
use Modules\Core\Services\SysColorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\EtatRealisationChapitreRequest;
use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\App\Exports\EtatRealisationChapitreExport;
use Modules\PkgApprentissage\App\Imports\EtatRealisationChapitreImport;
use Modules\Core\Services\ContextState;

class BaseEtatRealisationChapitreController extends AdminController
{
    protected $etatRealisationChapitreService;
    protected $sysColorService;

    public function __construct(EtatRealisationChapitreService $etatRealisationChapitreService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $etatRealisationChapitreService;
        $this->etatRealisationChapitreService = $etatRealisationChapitreService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('etatRealisationChapitre.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('etatRealisationChapitre');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $etatRealisationChapitres_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'etatRealisationChapitres_search',
                $this->viewState->get("filter.etatRealisationChapitre.etatRealisationChapitres_search")
            )],
            $request->except(['etatRealisationChapitres_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatRealisationChapitreService->prepareDataForIndexView($etatRealisationChapitres_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::etatRealisationChapitre._index', $etatRealisationChapitre_compact_value)->render();
            }else{
                return view($etatRealisationChapitre_partialViewName, $etatRealisationChapitre_compact_value)->render();
            }
        }

        return view('PkgApprentissage::etatRealisationChapitre.index', $etatRealisationChapitre_compact_value);
    }
    /**
     */
    public function create() {


        $itemEtatRealisationChapitre = $this->etatRealisationChapitreService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationChapitre._fields', compact('bulkEdit' ,'itemEtatRealisationChapitre', 'sysColors'));
        }
        return view('PkgApprentissage::etatRealisationChapitre.create', compact('bulkEdit' ,'itemEtatRealisationChapitre', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $etatRealisationChapitre_ids = $request->input('ids', []);

        if (!is_array($etatRealisationChapitre_ids) || count($etatRealisationChapitre_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemEtatRealisationChapitre = $this->etatRealisationChapitreService->find($etatRealisationChapitre_ids[0]);
         
 
        $sysColors = $this->sysColorService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemEtatRealisationChapitre = $this->etatRealisationChapitreService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationChapitre._fields', compact('bulkEdit', 'etatRealisationChapitre_ids', 'itemEtatRealisationChapitre', 'sysColors'));
        }
        return view('PkgApprentissage::etatRealisationChapitre.bulk-edit', compact('bulkEdit', 'etatRealisationChapitre_ids', 'itemEtatRealisationChapitre', 'sysColors'));
    }
    /**
     */
    public function store(EtatRealisationChapitreRequest $request) {
        $validatedData = $request->validated();
        $etatRealisationChapitre = $this->etatRealisationChapitreService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationChapitre,
                'modelName' => __('PkgApprentissage::etatRealisationChapitre.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatRealisationChapitre->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('etatRealisationChapitres.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatRealisationChapitre,
                'modelName' => __('PkgApprentissage::etatRealisationChapitre.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('etatRealisationChapitre.show_' . $id);

        $itemEtatRealisationChapitre = $this->etatRealisationChapitreService->edit($id);


        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationChapitre._show', array_merge(compact('itemEtatRealisationChapitre'),));
        }

        return view('PkgApprentissage::etatRealisationChapitre.show', array_merge(compact('itemEtatRealisationChapitre'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('etatRealisationChapitre.edit_' . $id);


        $itemEtatRealisationChapitre = $this->etatRealisationChapitreService->edit($id);


        $sysColors = $this->sysColorService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::etatRealisationChapitre._fields', array_merge(compact('bulkEdit' , 'itemEtatRealisationChapitre','sysColors'),));
        }

        return view('PkgApprentissage::etatRealisationChapitre.edit', array_merge(compact('bulkEdit' ,'itemEtatRealisationChapitre','sysColors'),));


    }
    /**
     */
    public function update(EtatRealisationChapitreRequest $request, string $id) {

        $validatedData = $request->validated();
        $etatRealisationChapitre = $this->etatRealisationChapitreService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationChapitre,
                'modelName' =>  __('PkgApprentissage::etatRealisationChapitre.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $etatRealisationChapitre->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('etatRealisationChapitres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatRealisationChapitre,
                'modelName' =>  __('PkgApprentissage::etatRealisationChapitre.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $etatRealisationChapitre_ids = $request->input('etatRealisationChapitre_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($etatRealisationChapitre_ids) || count($etatRealisationChapitre_ids) === 0) {
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
            $etatRealisationChapitre_ids,
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

        $etatRealisationChapitre = $this->etatRealisationChapitreService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationChapitre,
                'modelName' =>  __('PkgApprentissage::etatRealisationChapitre.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('etatRealisationChapitres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatRealisationChapitre,
                'modelName' =>  __('PkgApprentissage::etatRealisationChapitre.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $etatRealisationChapitre_ids = $request->input('ids', []);
        if (!is_array($etatRealisationChapitre_ids) || count($etatRealisationChapitre_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($etatRealisationChapitre_ids as $id) {
            $entity = $this->etatRealisationChapitreService->find($id);
            $this->etatRealisationChapitreService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($etatRealisationChapitre_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::etatRealisationChapitre.plural')
        ]));
    }

    public function export($format)
    {
        $etatRealisationChapitres_data = $this->etatRealisationChapitreService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatRealisationChapitreExport($etatRealisationChapitres_data,'csv'), 'etatRealisationChapitre_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatRealisationChapitreExport($etatRealisationChapitres_data,'xlsx'), 'etatRealisationChapitre_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatRealisationChapitreImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatRealisationChapitres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatRealisationChapitres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::etatRealisationChapitre.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatRealisationChapitres()
    {
        $etatRealisationChapitres = $this->etatRealisationChapitreService->all();
        return response()->json($etatRealisationChapitres);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (EtatRealisationChapitre) par ID, en format JSON.
     */
    public function getEtatRealisationChapitre(Request $request, $id)
    {
        try {
            $etatRealisationChapitre = $this->etatRealisationChapitreService->find($id);
            return response()->json($etatRealisationChapitre);
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
        $updatedEtatRealisationChapitre = $this->etatRealisationChapitreService->dataCalcul($data);

        return response()->json([
            'success' => true,
            'entity' => $updatedEtatRealisationChapitre
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
        $etatRealisationChapitreRequest = new EtatRealisationChapitreRequest();
        $fullRules = $etatRealisationChapitreRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:etat_realisation_chapitres,id'];
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