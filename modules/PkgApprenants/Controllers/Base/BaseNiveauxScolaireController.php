<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\NiveauxScolaireService;
use Modules\PkgApprenants\Services\ApprenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprenants\App\Requests\NiveauxScolaireRequest;
use Modules\PkgApprenants\Models\NiveauxScolaire;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprenants\App\Exports\NiveauxScolaireExport;
use Modules\PkgApprenants\App\Imports\NiveauxScolaireImport;
use Modules\Core\Services\ContextState;

class BaseNiveauxScolaireController extends AdminController
{
    protected $niveauxScolaireService;

    public function __construct(NiveauxScolaireService $niveauxScolaireService) {
        parent::__construct();
        $this->service  =  $niveauxScolaireService;
        $this->niveauxScolaireService = $niveauxScolaireService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('niveauxScolaire.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('niveauxScolaire');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $niveauxScolaires_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'niveauxScolaires_search',
                $this->viewState->get("filter.niveauxScolaire.niveauxScolaires_search")
            )],
            $request->except(['niveauxScolaires_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->niveauxScolaireService->prepareDataForIndexView($niveauxScolaires_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprenants::niveauxScolaire._index', $niveauxScolaire_compact_value)->render();
            }else{
                return view($niveauxScolaire_partialViewName, $niveauxScolaire_compact_value)->render();
            }
        }

        return view('PkgApprenants::niveauxScolaire.index', $niveauxScolaire_compact_value);
    }
    /**
     */
    public function create() {


        $itemNiveauxScolaire = $this->niveauxScolaireService->createInstance();
        


        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprenants::niveauxScolaire._fields', compact('bulkEdit' ,'itemNiveauxScolaire'));
        }
        return view('PkgApprenants::niveauxScolaire.create', compact('bulkEdit' ,'itemNiveauxScolaire'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $niveauxScolaire_ids = $request->input('ids', []);

        if (!is_array($niveauxScolaire_ids) || count($niveauxScolaire_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemNiveauxScolaire = $this->niveauxScolaireService->find($niveauxScolaire_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemNiveauxScolaire = $this->niveauxScolaireService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprenants::niveauxScolaire._fields', compact('bulkEdit', 'niveauxScolaire_ids', 'itemNiveauxScolaire'));
        }
        return view('PkgApprenants::niveauxScolaire.bulk-edit', compact('bulkEdit', 'niveauxScolaire_ids', 'itemNiveauxScolaire'));
    }
    /**
     */
    public function store(NiveauxScolaireRequest $request) {
        $validatedData = $request->validated();
        $niveauxScolaire = $this->niveauxScolaireService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' => __('PkgApprenants::niveauxScolaire.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $niveauxScolaire->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('niveauxScolaires.edit', ['niveauxScolaire' => $niveauxScolaire->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' => __('PkgApprenants::niveauxScolaire.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('niveauxScolaire.show_' . $id);

        $itemNiveauxScolaire = $this->niveauxScolaireService->edit($id);


        $this->viewState->set('scope.apprenant.niveaux_scolaire_id', $id);
        

        $apprenantService =  new ApprenantService();
        $apprenants_view_data = $apprenantService->prepareDataForIndexView();
        extract($apprenants_view_data);

        if (request()->ajax()) {
            return view('PkgApprenants::niveauxScolaire._show', array_merge(compact('itemNiveauxScolaire'),$apprenant_compact_value));
        }

        return view('PkgApprenants::niveauxScolaire.show', array_merge(compact('itemNiveauxScolaire'),$apprenant_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('niveauxScolaire.edit_' . $id);


        $itemNiveauxScolaire = $this->niveauxScolaireService->edit($id);




        $this->viewState->set('scope.apprenant.niveaux_scolaire_id', $id);
        

        $apprenantService =  new ApprenantService();
        $apprenants_view_data = $apprenantService->prepareDataForIndexView();
        extract($apprenants_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprenants::niveauxScolaire._edit', array_merge(compact('bulkEdit' , 'itemNiveauxScolaire',),$apprenant_compact_value));
        }

        return view('PkgApprenants::niveauxScolaire.edit', array_merge(compact('bulkEdit' ,'itemNiveauxScolaire',),$apprenant_compact_value));


    }
    /**
     */
    public function update(NiveauxScolaireRequest $request, string $id) {

        $validatedData = $request->validated();
        $niveauxScolaire = $this->niveauxScolaireService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' =>  __('PkgApprenants::niveauxScolaire.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $niveauxScolaire->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('niveauxScolaires.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' =>  __('PkgApprenants::niveauxScolaire.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $niveauxScolaire_ids = $request->input('niveauxScolaire_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($niveauxScolaire_ids) || count($niveauxScolaire_ids) === 0) {
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
            $niveauxScolaire_ids,
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

        $niveauxScolaire = $this->niveauxScolaireService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' =>  __('PkgApprenants::niveauxScolaire.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('niveauxScolaires.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' =>  __('PkgApprenants::niveauxScolaire.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $niveauxScolaire_ids = $request->input('ids', []);
        if (!is_array($niveauxScolaire_ids) || count($niveauxScolaire_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($niveauxScolaire_ids as $id) {
            $entity = $this->niveauxScolaireService->find($id);
            $this->niveauxScolaireService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($niveauxScolaire_ids) . ' éléments',
            'modelName' => __('PkgApprenants::niveauxScolaire.plural')
        ]));
    }

    public function export($format)
    {
        $niveauxScolaires_data = $this->niveauxScolaireService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new NiveauxScolaireExport($niveauxScolaires_data,'csv'), 'niveauxScolaire_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new NiveauxScolaireExport($niveauxScolaires_data,'xlsx'), 'niveauxScolaire_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new NiveauxScolaireImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('niveauxScolaires.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('niveauxScolaires.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprenants::niveauxScolaire.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNiveauxScolaires()
    {
        $niveauxScolaires = $this->niveauxScolaireService->all();
        return response()->json($niveauxScolaires);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (NiveauxScolaire) par ID, en format JSON.
     */
    public function getNiveauxScolaire(Request $request, $id)
    {
        try {
            $niveauxScolaire = $this->niveauxScolaireService->find($id);
            return response()->json($niveauxScolaire);
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
        $updatedNiveauxScolaire = $this->niveauxScolaireService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedNiveauxScolaire],
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
        $niveauxScolaireRequest = new NiveauxScolaireRequest();
        $fullRules = $niveauxScolaireRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:niveaux_scolaires,id'];
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