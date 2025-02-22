<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\NationaliteService;
use Modules\PkgApprenants\Services\ApprenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprenants\App\Requests\NationaliteRequest;
use Modules\PkgApprenants\Models\Nationalite;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprenants\App\Exports\NationaliteExport;
use Modules\PkgApprenants\App\Imports\NationaliteImport;
use Modules\Core\Services\ContextState;

class BaseNationaliteController extends AdminController
{
    protected $nationaliteService;

    public function __construct(NationaliteService $nationaliteService) {
        parent::__construct();
        $this->nationaliteService = $nationaliteService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('nationalite.index');


        // Extraire les paramètres de recherche, page, et filtres
        $nationalites_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('nationalites_search', $this->viewState->get("filter.nationalite.nationalites_search"))],
            $request->except(['nationalites_search', 'page', 'sort'])
        );

        // Paginer les nationalites
        $nationalites_data = $this->nationaliteService->paginate($nationalites_params);

        // Récupérer les statistiques et les champs filtrables
        $nationalites_stats = $this->nationaliteService->getnationaliteStats();
        $nationalites_filters = $this->nationaliteService->getFieldsFilterable();
        $nationalite_instance =  $this->nationaliteService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgApprenants::nationalite._table', compact('nationalites_data', 'nationalites_stats', 'nationalites_filters','nationalite_instance'))->render();
        }

        return view('PkgApprenants::nationalite.index', compact('nationalites_data', 'nationalites_stats', 'nationalites_filters','nationalite_instance'));
    }
    public function create() {


        $itemNationalite = $this->nationaliteService->createInstance();
        

        if (request()->ajax()) {
            return view('PkgApprenants::nationalite._fields', compact('itemNationalite'));
        }
        return view('PkgApprenants::nationalite.create', compact('itemNationalite'));
    }
    public function store(NationaliteRequest $request) {
        $validatedData = $request->validated();
        $nationalite = $this->nationaliteService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $nationalite,
                'modelName' => __('PkgApprenants::nationalite.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $nationalite->id]
            );
        }

        return redirect()->route('nationalites.edit',['nationalite' => $nationalite->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $nationalite,
                'modelName' => __('PkgApprenants::nationalite.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('nationalite.edit_' . $id);

        $itemNationalite = $this->nationaliteService->find($id);



        $this->viewState->set('scope.apprenant.nationalite_id', $id);
        $apprenantService =  new ApprenantService();
        $apprenants_data =  $itemNationalite->apprenants()->paginate(10);
        $apprenants_stats = $apprenantService->getapprenantStats();
        $apprenants_filters = $apprenantService->getFieldsFilterable();
        $apprenant_instance =  $apprenantService->createInstance();

        if (request()->ajax()) {
            return view('PkgApprenants::nationalite._edit', compact('itemNationalite', 'apprenants_data', 'apprenants_stats', 'apprenants_filters', 'apprenant_instance'));
        }

        return view('PkgApprenants::nationalite.edit', compact('itemNationalite', 'apprenants_data', 'apprenants_stats', 'apprenants_filters', 'apprenant_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('nationalite.edit_' . $id);

        $itemNationalite = $this->nationaliteService->find($id);



        $this->viewState->set('scope.apprenant.nationalite_id', $id);
        $apprenantService =  new ApprenantService();
        $apprenants_data =  $itemNationalite->apprenants()->paginate(10);
        $apprenants_stats = $apprenantService->getapprenantStats();
        $apprenants_filters = $apprenantService->getFieldsFilterable();
        $apprenant_instance =  $apprenantService->createInstance();

        if (request()->ajax()) {
            return view('PkgApprenants::nationalite._edit', compact('itemNationalite', 'apprenants_data', 'apprenants_stats', 'apprenants_filters', 'apprenant_instance'));
        }

        return view('PkgApprenants::nationalite.edit', compact('itemNationalite', 'apprenants_data', 'apprenants_stats', 'apprenants_filters', 'apprenant_instance'));

    }
    public function update(NationaliteRequest $request, string $id) {

        $validatedData = $request->validated();
        $nationalite = $this->nationaliteService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgApprenants::nationalite.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $nationalite->id]
            );
        }

        return redirect()->route('nationalites.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgApprenants::nationalite.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $nationalite = $this->nationaliteService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgApprenants::nationalite.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('nationalites.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgApprenants::nationalite.singular')
                ])
        );

    }

    public function export($format)
    {
        $nationalites_data = $this->nationaliteService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new NationaliteExport($nationalites_data,'csv'), 'nationalite_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new NationaliteExport($nationalites_data,'xlsx'), 'nationalite_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new NationaliteImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('nationalites.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('nationalites.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprenants::nationalite.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNationalites()
    {
        $nationalites = $this->nationaliteService->all();
        return response()->json($nationalites);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $nationalite = $this->nationaliteService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedNationalite = $this->nationaliteService->dataCalcul($nationalite);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedNationalite
        ]);
    }
    

}
