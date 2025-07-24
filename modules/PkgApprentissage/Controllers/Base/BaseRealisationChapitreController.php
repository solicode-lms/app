<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprentissage\Controllers\Base;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgApprentissage\Services\EtatRealisationChapitreService;
use Modules\PkgCompetences\Services\ChapitreService;
use Modules\PkgRealisationTache\Services\RealisationTacheService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprentissage\App\Requests\RealisationChapitreRequest;
use Modules\PkgApprentissage\Models\RealisationChapitre;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprentissage\App\Exports\RealisationChapitreExport;
use Modules\PkgApprentissage\App\Imports\RealisationChapitreImport;
use Modules\Core\Services\ContextState;

class BaseRealisationChapitreController extends AdminController
{
    protected $realisationChapitreService;
    protected $etatRealisationChapitreService;
    protected $chapitreService;
    protected $realisationTacheService;
    protected $realisationUaService;

    public function __construct(RealisationChapitreService $realisationChapitreService, EtatRealisationChapitreService $etatRealisationChapitreService, ChapitreService $chapitreService, RealisationTacheService $realisationTacheService, RealisationUaService $realisationUaService) {
        parent::__construct();
        $this->service  =  $realisationChapitreService;
        $this->realisationChapitreService = $realisationChapitreService;
        $this->etatRealisationChapitreService = $etatRealisationChapitreService;
        $this->chapitreService = $chapitreService;
        $this->realisationTacheService = $realisationTacheService;
        $this->realisationUaService = $realisationUaService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('realisationChapitre.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('realisationChapitre');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $realisationChapitres_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'realisationChapitres_search',
                $this->viewState->get("filter.realisationChapitre.realisationChapitres_search")
            )],
            $request->except(['realisationChapitres_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationChapitreService->prepareDataForIndexView($realisationChapitres_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgApprentissage::realisationChapitre._index', $realisationChapitre_compact_value)->render();
            }else{
                return view($realisationChapitre_partialViewName, $realisationChapitre_compact_value)->render();
            }
        }

        return view('PkgApprentissage::realisationChapitre.index', $realisationChapitre_compact_value);
    }
    /**
     */
    public function create() {


        $itemRealisationChapitre = $this->realisationChapitreService->createInstance();
        

        $realisationUas = $this->realisationUaService->all();
        $realisationTaches = $this->realisationTacheService->all();
        $chapitres = $this->chapitreService->all();
        $etatRealisationChapitres = $this->etatRealisationChapitreService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationChapitre._fields', compact('bulkEdit' ,'itemRealisationChapitre', 'etatRealisationChapitres', 'chapitres', 'realisationTaches', 'realisationUas'));
        }
        return view('PkgApprentissage::realisationChapitre.create', compact('bulkEdit' ,'itemRealisationChapitre', 'etatRealisationChapitres', 'chapitres', 'realisationTaches', 'realisationUas'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $realisationChapitre_ids = $request->input('ids', []);

        if (!is_array($realisationChapitre_ids) || count($realisationChapitre_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemRealisationChapitre = $this->realisationChapitreService->find($realisationChapitre_ids[0]);
         
 
        $realisationUas = $this->realisationUaService->all();
        $realisationTaches = $this->realisationTacheService->all();
        $chapitres = $this->chapitreService->all();
        $etatRealisationChapitres = $this->etatRealisationChapitreService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemRealisationChapitre = $this->realisationChapitreService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgApprentissage::realisationChapitre._fields', compact('bulkEdit', 'realisationChapitre_ids', 'itemRealisationChapitre', 'etatRealisationChapitres', 'chapitres', 'realisationTaches', 'realisationUas'));
        }
        return view('PkgApprentissage::realisationChapitre.bulk-edit', compact('bulkEdit', 'realisationChapitre_ids', 'itemRealisationChapitre', 'etatRealisationChapitres', 'chapitres', 'realisationTaches', 'realisationUas'));
    }
    /**
     */
    public function store(RealisationChapitreRequest $request) {
        $validatedData = $request->validated();
        $realisationChapitre = $this->realisationChapitreService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' => __('PkgApprentissage::realisationChapitre.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $realisationChapitre->id]
            );
        }

        return redirect()->route('realisationChapitres.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' => __('PkgApprentissage::realisationChapitre.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('realisationChapitre.show_' . $id);

        $itemRealisationChapitre = $this->realisationChapitreService->edit($id);


        if (request()->ajax()) {
            return view('PkgApprentissage::realisationChapitre._show', array_merge(compact('itemRealisationChapitre'),));
        }

        return view('PkgApprentissage::realisationChapitre.show', array_merge(compact('itemRealisationChapitre'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationChapitre.edit_' . $id);


        $itemRealisationChapitre = $this->realisationChapitreService->edit($id);


        $realisationUas = $this->realisationUaService->all();
        $realisationTaches = $this->realisationTacheService->all();
        $chapitres = $this->chapitreService->all();
        $etatRealisationChapitres = $this->etatRealisationChapitreService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgApprentissage::realisationChapitre._fields', array_merge(compact('bulkEdit' , 'itemRealisationChapitre','etatRealisationChapitres', 'chapitres', 'realisationTaches', 'realisationUas'),));
        }

        return view('PkgApprentissage::realisationChapitre.edit', array_merge(compact('bulkEdit' ,'itemRealisationChapitre','etatRealisationChapitres', 'chapitres', 'realisationTaches', 'realisationUas'),));


    }
    /**
     */
    public function update(RealisationChapitreRequest $request, string $id) {

        $validatedData = $request->validated();
        $realisationChapitre = $this->realisationChapitreService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' =>  __('PkgApprentissage::realisationChapitre.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $realisationChapitre->id]
            );
        }

        return redirect()->route('realisationChapitres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' =>  __('PkgApprentissage::realisationChapitre.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $realisationChapitre_ids = $request->input('realisationChapitre_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($realisationChapitre_ids) || count($realisationChapitre_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($realisationChapitre_ids as $id) {
            $entity = $this->realisationChapitreService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->realisationChapitreService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->realisationChapitreService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $realisationChapitre = $this->realisationChapitreService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' =>  __('PkgApprentissage::realisationChapitre.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('realisationChapitres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' =>  __('PkgApprentissage::realisationChapitre.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $realisationChapitre_ids = $request->input('ids', []);
        if (!is_array($realisationChapitre_ids) || count($realisationChapitre_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($realisationChapitre_ids as $id) {
            $entity = $this->realisationChapitreService->find($id);
            $this->realisationChapitreService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($realisationChapitre_ids) . ' éléments',
            'modelName' => __('PkgApprentissage::realisationChapitre.plural')
        ]));
    }

    public function export($format)
    {
        $realisationChapitres_data = $this->realisationChapitreService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationChapitreExport($realisationChapitres_data,'csv'), 'realisationChapitre_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationChapitreExport($realisationChapitres_data,'xlsx'), 'realisationChapitre_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationChapitreImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationChapitres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationChapitres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprentissage::realisationChapitre.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationChapitres()
    {
        $realisationChapitres = $this->realisationChapitreService->all();
        return response()->json($realisationChapitres);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $realisationChapitre = $this->realisationChapitreService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedRealisationChapitre = $this->realisationChapitreService->dataCalcul($realisationChapitre);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedRealisationChapitre
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
        $realisationChapitreRequest = new RealisationChapitreRequest();
        $fullRules = $realisationChapitreRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:realisation_chapitres,id'];
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