<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\NiveauDifficulteService;
use Modules\PkgFormation\Services\FormateurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\NiveauDifficulteRequest;
use Modules\PkgCompetences\Models\NiveauDifficulte;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\NiveauDifficulteExport;
use Modules\PkgCompetences\App\Imports\NiveauDifficulteImport;
use Modules\Core\Services\ContextState;

class BaseNiveauDifficulteController extends AdminController
{
    protected $niveauDifficulteService;
    protected $formateurService;

    public function __construct(NiveauDifficulteService $niveauDifficulteService, FormateurService $formateurService) {
        parent::__construct();
        $this->service  =  $niveauDifficulteService;
        $this->niveauDifficulteService = $niveauDifficulteService;
        $this->formateurService = $formateurService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('niveauDifficulte.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('niveauDifficulte');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.niveauDifficulte.formateur_id') == null){
           $this->viewState->init('filter.niveauDifficulte.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $niveauDifficultes_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'niveauDifficultes_search',
                $this->viewState->get("filter.niveauDifficulte.niveauDifficultes_search")
            )],
            $request->except(['niveauDifficultes_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->niveauDifficulteService->prepareDataForIndexView($niveauDifficultes_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::niveauDifficulte._index', $niveauDifficulte_compact_value)->render();
            }else{
                return view($niveauDifficulte_partialViewName, $niveauDifficulte_compact_value)->render();
            }
        }

        return view('PkgCompetences::niveauDifficulte.index', $niveauDifficulte_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.niveauDifficulte.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemNiveauDifficulte = $this->niveauDifficulteService->createInstance();
        

        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::niveauDifficulte._fields', compact('itemNiveauDifficulte', 'formateurs'));
        }
        return view('PkgCompetences::niveauDifficulte.create', compact('itemNiveauDifficulte', 'formateurs'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $niveauDifficulte_ids = $request->input('ids', []);

        if (!is_array($niveauDifficulte_ids) || count($niveauDifficulte_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.niveauDifficulte.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
 
         $itemNiveauDifficulte = $this->niveauDifficulteService->find($niveauDifficulte_ids[0]);
         
 
        $formateurs = $this->formateurService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemNiveauDifficulte = $this->niveauDifficulteService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::niveauDifficulte._fields', compact('bulkEdit', 'niveauDifficulte_ids', 'itemNiveauDifficulte', 'formateurs'));
        }
        return view('PkgCompetences::niveauDifficulte.bulk-edit', compact('bulkEdit', 'niveauDifficulte_ids', 'itemNiveauDifficulte', 'formateurs'));
    }
    /**
     */
    public function store(NiveauDifficulteRequest $request) {
        $validatedData = $request->validated();
        $niveauDifficulte = $this->niveauDifficulteService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $niveauDifficulte,
                'modelName' => __('PkgCompetences::niveauDifficulte.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $niveauDifficulte->id]
            );
        }

        return redirect()->route('niveauDifficultes.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $niveauDifficulte,
                'modelName' => __('PkgCompetences::niveauDifficulte.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('niveauDifficulte.edit_' . $id);


        $itemNiveauDifficulte = $this->niveauDifficulteService->edit($id);
        $this->authorize('view', $itemNiveauDifficulte);


        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::niveauDifficulte._fields', array_merge(compact('itemNiveauDifficulte','formateurs'),));
        }

        return view('PkgCompetences::niveauDifficulte.edit', array_merge(compact('itemNiveauDifficulte','formateurs'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('niveauDifficulte.edit_' . $id);


        $itemNiveauDifficulte = $this->niveauDifficulteService->edit($id);
        $this->authorize('edit', $itemNiveauDifficulte);


        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::niveauDifficulte._fields', array_merge(compact('itemNiveauDifficulte','formateurs'),));
        }

        return view('PkgCompetences::niveauDifficulte.edit', array_merge(compact('itemNiveauDifficulte','formateurs'),));


    }
    /**
     */
    public function update(NiveauDifficulteRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $niveauDifficulte = $this->niveauDifficulteService->find($id);
        $this->authorize('update', $niveauDifficulte);

        $validatedData = $request->validated();
        $niveauDifficulte = $this->niveauDifficulteService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $niveauDifficulte,
                'modelName' =>  __('PkgCompetences::niveauDifficulte.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $niveauDifficulte->id]
            );
        }

        return redirect()->route('niveauDifficultes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $niveauDifficulte,
                'modelName' =>  __('PkgCompetences::niveauDifficulte.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $niveauDifficulte_ids = $request->input('niveauDifficulte_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($niveauDifficulte_ids) || count($niveauDifficulte_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($niveauDifficulte_ids as $id) {
            $entity = $this->niveauDifficulteService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->niveauDifficulteService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->niveauDifficulteService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $niveauDifficulte = $this->niveauDifficulteService->find($id);
        $this->authorize('delete', $niveauDifficulte);

        $niveauDifficulte = $this->niveauDifficulteService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauDifficulte,
                'modelName' =>  __('PkgCompetences::niveauDifficulte.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('niveauDifficultes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauDifficulte,
                'modelName' =>  __('PkgCompetences::niveauDifficulte.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $niveauDifficulte_ids = $request->input('ids', []);
        if (!is_array($niveauDifficulte_ids) || count($niveauDifficulte_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($niveauDifficulte_ids as $id) {
            $entity = $this->niveauDifficulteService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $niveauDifficulte = $this->niveauDifficulteService->find($id);
            $this->authorize('delete', $niveauDifficulte);
            $this->niveauDifficulteService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($niveauDifficulte_ids) . ' éléments',
            'modelName' => __('PkgCompetences::niveauDifficulte.plural')
        ]));
    }

    public function export($format)
    {
        $niveauDifficultes_data = $this->niveauDifficulteService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new NiveauDifficulteExport($niveauDifficultes_data,'csv'), 'niveauDifficulte_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new NiveauDifficulteExport($niveauDifficultes_data,'xlsx'), 'niveauDifficulte_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new NiveauDifficulteImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('niveauDifficultes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('niveauDifficultes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::niveauDifficulte.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNiveauDifficultes()
    {
        $niveauDifficultes = $this->niveauDifficulteService->all();
        return response()->json($niveauDifficultes);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $niveauDifficulte = $this->niveauDifficulteService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedNiveauDifficulte = $this->niveauDifficulteService->dataCalcul($niveauDifficulte);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedNiveauDifficulte
        ]);
    }
    

}