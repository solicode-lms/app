<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers\Base;
use Modules\PkgGestionTaches\Services\PrioriteTacheService;
use Modules\PkgFormation\Services\FormateurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\App\Requests\PrioriteTacheRequest;
use Modules\PkgGestionTaches\Models\PrioriteTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGestionTaches\App\Exports\PrioriteTacheExport;
use Modules\PkgGestionTaches\App\Imports\PrioriteTacheImport;
use Modules\Core\Services\ContextState;

class BasePrioriteTacheController extends AdminController
{
    protected $prioriteTacheService;
    protected $formateurService;

    public function __construct(PrioriteTacheService $prioriteTacheService, FormateurService $formateurService) {
        parent::__construct();
        $this->service  =  $prioriteTacheService;
        $this->prioriteTacheService = $prioriteTacheService;
        $this->formateurService = $formateurService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('prioriteTache.index');
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.prioriteTache.formateur_id') == null){
           $this->viewState->init('scope.prioriteTache.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



        // Extraire les paramètres de recherche, page, et filtres
        $prioriteTaches_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('prioriteTaches_search', $this->viewState->get("filter.prioriteTache.prioriteTaches_search"))],
            $request->except(['prioriteTaches_search', 'page', 'sort'])
        );

        // Paginer les prioriteTaches
        $prioriteTaches_data = $this->prioriteTacheService->paginate($prioriteTaches_params);

        // Récupérer les statistiques et les champs filtrables
        $prioriteTaches_stats = $this->prioriteTacheService->getprioriteTacheStats();
        $this->viewState->set('stats.prioriteTache.stats'  , $prioriteTaches_stats);
        $prioriteTaches_filters = $this->prioriteTacheService->getFieldsFilterable();
        $prioriteTache_instance =  $this->prioriteTacheService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGestionTaches::prioriteTache._table', compact('prioriteTaches_data', 'prioriteTaches_stats', 'prioriteTaches_filters','prioriteTache_instance'))->render();
        }

        return view('PkgGestionTaches::prioriteTache.index', compact('prioriteTaches_data', 'prioriteTaches_stats', 'prioriteTaches_filters','prioriteTache_instance'));
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.prioriteTache.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemPrioriteTache = $this->prioriteTacheService->createInstance();
        

        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgGestionTaches::prioriteTache._fields', compact('itemPrioriteTache', 'formateurs'));
        }
        return view('PkgGestionTaches::prioriteTache.create', compact('itemPrioriteTache', 'formateurs'));
    }
    public function store(PrioriteTacheRequest $request) {
        $validatedData = $request->validated();
        $prioriteTache = $this->prioriteTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' => __('PkgGestionTaches::prioriteTache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $prioriteTache->id]
            );
        }

        return redirect()->route('prioriteTaches.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' => __('PkgGestionTaches::prioriteTache.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('prioriteTache.edit_' . $id);


        $itemPrioriteTache = $this->prioriteTacheService->find($id);
        $this->authorize('view', $itemPrioriteTache);


        $formateurs = $this->formateurService->all();
        

        if (request()->ajax()) {
            return view('PkgGestionTaches::prioriteTache._fields', compact('itemPrioriteTache', 'formateurs'));
        }

        return view('PkgGestionTaches::prioriteTache.edit', compact('itemPrioriteTache', 'formateurs'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('prioriteTache.edit_' . $id);


        $itemPrioriteTache = $this->prioriteTacheService->find($id);
        $this->authorize('edit', $itemPrioriteTache);


        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgGestionTaches::prioriteTache._fields', compact('itemPrioriteTache', 'formateurs'));
        }

        return view('PkgGestionTaches::prioriteTache.edit', compact('itemPrioriteTache', 'formateurs'));

    }
    public function update(PrioriteTacheRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $prioriteTache = $this->prioriteTacheService->find($id);
        $this->authorize('update', $prioriteTache);

        $validatedData = $request->validated();
        $prioriteTache = $this->prioriteTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' =>  __('PkgGestionTaches::prioriteTache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $prioriteTache->id]
            );
        }

        return redirect()->route('prioriteTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' =>  __('PkgGestionTaches::prioriteTache.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $prioriteTache = $this->prioriteTacheService->find($id);
        $this->authorize('delete', $prioriteTache);

        $prioriteTache = $this->prioriteTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' =>  __('PkgGestionTaches::prioriteTache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('prioriteTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $prioriteTache,
                'modelName' =>  __('PkgGestionTaches::prioriteTache.singular')
                ])
        );

    }

    public function export($format)
    {
        $prioriteTaches_data = $this->prioriteTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new PrioriteTacheExport($prioriteTaches_data,'csv'), 'prioriteTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new PrioriteTacheExport($prioriteTaches_data,'xlsx'), 'prioriteTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new PrioriteTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('prioriteTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('prioriteTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGestionTaches::prioriteTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getPrioriteTaches()
    {
        $prioriteTaches = $this->prioriteTacheService->all();
        return response()->json($prioriteTaches);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $prioriteTache = $this->prioriteTacheService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedPrioriteTache = $this->prioriteTacheService->dataCalcul($prioriteTache);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedPrioriteTache
        ]);
    }
    

}
