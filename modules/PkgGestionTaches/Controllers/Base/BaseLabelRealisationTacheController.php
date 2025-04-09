<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers\Base;
use Modules\PkgGestionTaches\Services\LabelRealisationTacheService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\Core\Services\SysColorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\App\Requests\LabelRealisationTacheRequest;
use Modules\PkgGestionTaches\Models\LabelRealisationTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGestionTaches\App\Exports\LabelRealisationTacheExport;
use Modules\PkgGestionTaches\App\Imports\LabelRealisationTacheImport;
use Modules\Core\Services\ContextState;

class BaseLabelRealisationTacheController extends AdminController
{
    protected $labelRealisationTacheService;
    protected $formateurService;
    protected $sysColorService;

    public function __construct(LabelRealisationTacheService $labelRealisationTacheService, FormateurService $formateurService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $labelRealisationTacheService;
        $this->labelRealisationTacheService = $labelRealisationTacheService;
        $this->formateurService = $formateurService;
        $this->sysColorService = $sysColorService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('labelRealisationTache.index');
        
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.labelRealisationTache.formateur_id') == null){
           $this->viewState->init('scope.labelRealisationTache.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $labelRealisationTaches_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'labelRealisationTaches_search',
                $this->viewState->get("filter.labelRealisationTache.labelRealisationTaches_search")
            )],
            $request->except(['labelRealisationTaches_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->labelRealisationTacheService->prepareDataForIndexView($labelRealisationTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($labelRealisationTache_partialViewName, $labelRealisationTache_compact_value)->render();
        }

        return view('PkgGestionTaches::labelRealisationTache.index', $labelRealisationTache_compact_value);
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.labelRealisationTache.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemLabelRealisationTache = $this->labelRealisationTacheService->createInstance();
        

        $formateurs = $this->formateurService->all();
        $sysColors = $this->sysColorService->all();

        if (request()->ajax()) {
            return view('PkgGestionTaches::labelRealisationTache._fields', compact('itemLabelRealisationTache', 'formateurs', 'sysColors'));
        }
        return view('PkgGestionTaches::labelRealisationTache.create', compact('itemLabelRealisationTache', 'formateurs', 'sysColors'));
    }
    public function store(LabelRealisationTacheRequest $request) {
        $validatedData = $request->validated();
        $labelRealisationTache = $this->labelRealisationTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $labelRealisationTache,
                'modelName' => __('PkgGestionTaches::labelRealisationTache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $labelRealisationTache->id]
            );
        }

        return redirect()->route('labelRealisationTaches.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $labelRealisationTache,
                'modelName' => __('PkgGestionTaches::labelRealisationTache.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('labelRealisationTache.edit_' . $id);


        $itemLabelRealisationTache = $this->labelRealisationTacheService->edit($id);
        $this->authorize('view', $itemLabelRealisationTache);


        $formateurs = $this->formateurService->all();
        $sysColors = $this->sysColorService->all();


        if (request()->ajax()) {
            return view('PkgGestionTaches::labelRealisationTache._fields', array_merge(compact('itemLabelRealisationTache','formateurs', 'sysColors'),));
        }

        return view('PkgGestionTaches::labelRealisationTache.edit', array_merge(compact('itemLabelRealisationTache','formateurs', 'sysColors'),));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('labelRealisationTache.edit_' . $id);


        $itemLabelRealisationTache = $this->labelRealisationTacheService->edit($id);
        $this->authorize('edit', $itemLabelRealisationTache);


        $formateurs = $this->formateurService->all();
        $sysColors = $this->sysColorService->all();


        if (request()->ajax()) {
            return view('PkgGestionTaches::labelRealisationTache._fields', array_merge(compact('itemLabelRealisationTache','formateurs', 'sysColors'),));
        }

        return view('PkgGestionTaches::labelRealisationTache.edit', array_merge(compact('itemLabelRealisationTache','formateurs', 'sysColors'),));


    }
    public function update(LabelRealisationTacheRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $labelRealisationTache = $this->labelRealisationTacheService->find($id);
        $this->authorize('update', $labelRealisationTache);

        $validatedData = $request->validated();
        $labelRealisationTache = $this->labelRealisationTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $labelRealisationTache,
                'modelName' =>  __('PkgGestionTaches::labelRealisationTache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $labelRealisationTache->id]
            );
        }

        return redirect()->route('labelRealisationTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $labelRealisationTache,
                'modelName' =>  __('PkgGestionTaches::labelRealisationTache.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $labelRealisationTache = $this->labelRealisationTacheService->find($id);
        $this->authorize('delete', $labelRealisationTache);

        $labelRealisationTache = $this->labelRealisationTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $labelRealisationTache,
                'modelName' =>  __('PkgGestionTaches::labelRealisationTache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('labelRealisationTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $labelRealisationTache,
                'modelName' =>  __('PkgGestionTaches::labelRealisationTache.singular')
                ])
        );

    }

    public function export($format)
    {
        $labelRealisationTaches_data = $this->labelRealisationTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new LabelRealisationTacheExport($labelRealisationTaches_data,'csv'), 'labelRealisationTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new LabelRealisationTacheExport($labelRealisationTaches_data,'xlsx'), 'labelRealisationTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new LabelRealisationTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('labelRealisationTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('labelRealisationTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGestionTaches::labelRealisationTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getLabelRealisationTaches()
    {
        $labelRealisationTaches = $this->labelRealisationTacheService->all();
        return response()->json($labelRealisationTaches);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $labelRealisationTache = $this->labelRealisationTacheService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedLabelRealisationTache = $this->labelRealisationTacheService->dataCalcul($labelRealisationTache);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedLabelRealisationTache
        ]);
    }
    

}