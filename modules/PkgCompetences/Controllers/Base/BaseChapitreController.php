<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\ChapitreService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgCompetences\Services\UniteApprentissageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\ChapitreRequest;
use Modules\PkgCompetences\Models\Chapitre;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\ChapitreExport;
use Modules\PkgCompetences\App\Imports\ChapitreImport;
use Modules\Core\Services\ContextState;

class BaseChapitreController extends AdminController
{
    protected $chapitreService;
    protected $formateurService;
    protected $uniteApprentissageService;

    public function __construct(ChapitreService $chapitreService, FormateurService $formateurService, UniteApprentissageService $uniteApprentissageService) {
        parent::__construct();
        $this->service  =  $chapitreService;
        $this->chapitreService = $chapitreService;
        $this->formateurService = $formateurService;
        $this->uniteApprentissageService = $uniteApprentissageService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('chapitre.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('chapitre');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $chapitres_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'chapitres_search',
                $this->viewState->get("filter.chapitre.chapitres_search")
            )],
            $request->except(['chapitres_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->chapitreService->prepareDataForIndexView($chapitres_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCompetences::chapitre._index', $chapitre_compact_value)->render();
            }else{
                return view($chapitre_partialViewName, $chapitre_compact_value)->render();
            }
        }

        return view('PkgCompetences::chapitre.index', $chapitre_compact_value);
    }
    /**
     */
    public function create() {


        $itemChapitre = $this->chapitreService->createInstance();
        

        $uniteApprentissages = $this->uniteApprentissageService->all();
        $formateurs = $this->formateurService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCompetences::chapitre._fields', compact('bulkEdit' ,'itemChapitre', 'formateurs', 'uniteApprentissages'));
        }
        return view('PkgCompetences::chapitre.create', compact('bulkEdit' ,'itemChapitre', 'formateurs', 'uniteApprentissages'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $chapitre_ids = $request->input('ids', []);

        if (!is_array($chapitre_ids) || count($chapitre_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemChapitre = $this->chapitreService->find($chapitre_ids[0]);
         
 
        $uniteApprentissages = $this->uniteApprentissageService->all();
        $formateurs = $this->formateurService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemChapitre = $this->chapitreService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCompetences::chapitre._fields', compact('bulkEdit', 'chapitre_ids', 'itemChapitre', 'formateurs', 'uniteApprentissages'));
        }
        return view('PkgCompetences::chapitre.bulk-edit', compact('bulkEdit', 'chapitre_ids', 'itemChapitre', 'formateurs', 'uniteApprentissages'));
    }
    /**
     */
    public function store(ChapitreRequest $request) {
        $validatedData = $request->validated();
        $chapitre = $this->chapitreService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $chapitre,
                'modelName' => __('PkgCompetences::chapitre.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $chapitre->id]
            );
        }

        return redirect()->route('chapitres.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $chapitre,
                'modelName' => __('PkgCompetences::chapitre.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('chapitre.show_' . $id);

        $itemChapitre = $this->chapitreService->edit($id);


        if (request()->ajax()) {
            return view('PkgCompetences::chapitre._show', array_merge(compact('itemChapitre'),));
        }

        return view('PkgCompetences::chapitre.show', array_merge(compact('itemChapitre'),));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('chapitre.edit_' . $id);


        $itemChapitre = $this->chapitreService->edit($id);


        $uniteApprentissages = $this->uniteApprentissageService->all();
        $formateurs = $this->formateurService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCompetences::chapitre._fields', array_merge(compact('bulkEdit' , 'itemChapitre','formateurs', 'uniteApprentissages'),));
        }

        return view('PkgCompetences::chapitre.edit', array_merge(compact('bulkEdit' ,'itemChapitre','formateurs', 'uniteApprentissages'),));


    }
    /**
     */
    public function update(ChapitreRequest $request, string $id) {

        $validatedData = $request->validated();
        $chapitre = $this->chapitreService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $chapitre,
                'modelName' =>  __('PkgCompetences::chapitre.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $chapitre->id]
            );
        }

        return redirect()->route('chapitres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $chapitre,
                'modelName' =>  __('PkgCompetences::chapitre.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $chapitre_ids = $request->input('chapitre_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($chapitre_ids) || count($chapitre_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($chapitre_ids as $id) {
            $entity = $this->chapitreService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->chapitreService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->chapitreService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $chapitre = $this->chapitreService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $chapitre,
                'modelName' =>  __('PkgCompetences::chapitre.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('chapitres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $chapitre,
                'modelName' =>  __('PkgCompetences::chapitre.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $chapitre_ids = $request->input('ids', []);
        if (!is_array($chapitre_ids) || count($chapitre_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($chapitre_ids as $id) {
            $entity = $this->chapitreService->find($id);
            $this->chapitreService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($chapitre_ids) . ' éléments',
            'modelName' => __('PkgCompetences::chapitre.plural')
        ]));
    }

    public function export($format)
    {
        $chapitres_data = $this->chapitreService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new ChapitreExport($chapitres_data,'csv'), 'chapitre_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new ChapitreExport($chapitres_data,'xlsx'), 'chapitre_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new ChapitreImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('chapitres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('chapitres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::chapitre.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getChapitres()
    {
        $chapitres = $this->chapitreService->all();
        return response()->json($chapitres);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $chapitre = $this->chapitreService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedChapitre = $this->chapitreService->dataCalcul($chapitre);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedChapitre
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
        $chapitreRequest = new ChapitreRequest();
        $fullRules = $chapitreRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:chapitres,id'];
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