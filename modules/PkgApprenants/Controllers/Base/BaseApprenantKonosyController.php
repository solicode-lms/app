<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\ApprenantKonosyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprenants\App\Requests\ApprenantKonosyRequest;
use Modules\PkgApprenants\Models\ApprenantKonosy;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprenants\App\Exports\ApprenantKonosyExport;
use Modules\PkgApprenants\App\Imports\ApprenantKonosyImport;
use Modules\Core\Services\ContextState;

class BaseApprenantKonosyController extends AdminController
{
    protected $apprenantKonosyService;

    public function __construct(ApprenantKonosyService $apprenantKonosyService) {
        parent::__construct();
        $this->apprenantKonosyService = $apprenantKonosyService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('apprenantKonosy.index');


        // Extraire les paramètres de recherche, page, et filtres
        $apprenantKonosies_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('apprenantKonosies_search', $this->viewState->get("filter.apprenantKonosy.apprenantKonosies_search"))],
            $request->except(['apprenantKonosies_search', 'page', 'sort'])
        );

        // Paginer les apprenantKonosies
        $apprenantKonosies_data = $this->apprenantKonosyService->paginate($apprenantKonosies_params);

        // Récupérer les statistiques et les champs filtrables
        $apprenantKonosies_stats = $this->apprenantKonosyService->getapprenantKonosyStats();
        $this->viewState->set('stats.apprenantKonosy.stats'  , $apprenantKonosies_stats);
        $apprenantKonosies_filters = $this->apprenantKonosyService->getFieldsFilterable();
        $apprenantKonosy_instance =  $this->apprenantKonosyService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgApprenants::apprenantKonosy._table', compact('apprenantKonosies_data', 'apprenantKonosies_stats', 'apprenantKonosies_filters','apprenantKonosy_instance'))->render();
        }

        return view('PkgApprenants::apprenantKonosy.index', compact('apprenantKonosies_data', 'apprenantKonosies_stats', 'apprenantKonosies_filters','apprenantKonosy_instance'));
    }
    public function create() {


        $itemApprenantKonosy = $this->apprenantKonosyService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgApprenants::apprenantKonosy._fields', compact('itemApprenantKonosy'));
        }
        return view('PkgApprenants::apprenantKonosy.create', compact('itemApprenantKonosy'));
    }
    public function store(ApprenantKonosyRequest $request) {
        $validatedData = $request->validated();
        $apprenantKonosy = $this->apprenantKonosyService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' => __('PkgApprenants::apprenantKonosy.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $apprenantKonosy->id]
            );
        }

        return redirect()->route('apprenantKonosies.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' => __('PkgApprenants::apprenantKonosy.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('apprenantKonosy.edit_' . $id);


        $itemApprenantKonosy = $this->apprenantKonosyService->find($id);




        if (request()->ajax()) {
            return view('PkgApprenants::apprenantKonosy._fields', compact('itemApprenantKonosy'));
        }

        return view('PkgApprenants::apprenantKonosy.edit', compact('itemApprenantKonosy'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('apprenantKonosy.edit_' . $id);


        $itemApprenantKonosy = $this->apprenantKonosyService->find($id);




        if (request()->ajax()) {
            return view('PkgApprenants::apprenantKonosy._fields', compact('itemApprenantKonosy'));
        }

        return view('PkgApprenants::apprenantKonosy.edit', compact('itemApprenantKonosy'));

    }
    public function update(ApprenantKonosyRequest $request, string $id) {

        $validatedData = $request->validated();
        $apprenantKonosy = $this->apprenantKonosyService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' =>  __('PkgApprenants::apprenantKonosy.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $apprenantKonosy->id]
            );
        }

        return redirect()->route('apprenantKonosies.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' =>  __('PkgApprenants::apprenantKonosy.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $apprenantKonosy = $this->apprenantKonosyService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' =>  __('PkgApprenants::apprenantKonosy.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('apprenantKonosies.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' =>  __('PkgApprenants::apprenantKonosy.singular')
                ])
        );

    }

    public function export($format)
    {
        $apprenantKonosies_data = $this->apprenantKonosyService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new ApprenantKonosyExport($apprenantKonosies_data,'csv'), 'apprenantKonosy_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new ApprenantKonosyExport($apprenantKonosies_data,'xlsx'), 'apprenantKonosy_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new ApprenantKonosyImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('apprenantKonosies.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('apprenantKonosies.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprenants::apprenantKonosy.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getApprenantKonosies()
    {
        $apprenantKonosies = $this->apprenantKonosyService->all();
        return response()->json($apprenantKonosies);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $apprenantKonosy = $this->apprenantKonosyService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedApprenantKonosy = $this->apprenantKonosyService->dataCalcul($apprenantKonosy);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedApprenantKonosy
        ]);
    }
    

}
