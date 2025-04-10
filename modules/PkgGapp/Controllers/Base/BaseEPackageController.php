<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EPackageService;
use Modules\PkgGapp\Services\EModelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGapp\App\Requests\EPackageRequest;
use Modules\PkgGapp\Models\EPackage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\EPackageExport;
use Modules\PkgGapp\App\Imports\EPackageImport;
use Modules\Core\Services\ContextState;

class BaseEPackageController extends AdminController
{
    protected $ePackageService;

    public function __construct(EPackageService $ePackageService) {
        parent::__construct();
        $this->service  =  $ePackageService;
        $this->ePackageService = $ePackageService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('ePackage.index');
        



         // Extraire les paramètres de recherche, pagination, filtres
        $ePackages_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'ePackages_search',
                $this->viewState->get("filter.ePackage.ePackages_search")
            )],
            $request->except(['ePackages_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->ePackageService->prepareDataForIndexView($ePackages_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($ePackage_partialViewName, $ePackage_compact_value)->render();
        }

        return view('PkgGapp::ePackage.index', $ePackage_compact_value);
    }
    public function create() {


        $itemEPackage = $this->ePackageService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgGapp::ePackage._fields', compact('itemEPackage'));
        }
        return view('PkgGapp::ePackage.create', compact('itemEPackage'));
    }
    public function store(EPackageRequest $request) {
        $validatedData = $request->validated();
        $ePackage = $this->ePackageService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $ePackage,
                'modelName' => __('PkgGapp::ePackage.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $ePackage->id]
            );
        }

        return redirect()->route('ePackages.edit',['ePackage' => $ePackage->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $ePackage,
                'modelName' => __('PkgGapp::ePackage.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('ePackage.edit_' . $id);


        $itemEPackage = $this->ePackageService->edit($id);




        $this->viewState->set('scope.eModel.e_package_id', $id);
        

        $eModelService =  new EModelService();
        $eModels_view_data = $eModelService->prepareDataForIndexView();
        extract($eModels_view_data);

        if (request()->ajax()) {
            return view('PkgGapp::ePackage._edit', array_merge(compact('itemEPackage',),$eModel_compact_value));
        }

        return view('PkgGapp::ePackage.edit', array_merge(compact('itemEPackage',),$eModel_compact_value));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('ePackage.edit_' . $id);


        $itemEPackage = $this->ePackageService->edit($id);




        $this->viewState->set('scope.eModel.e_package_id', $id);
        

        $eModelService =  new EModelService();
        $eModels_view_data = $eModelService->prepareDataForIndexView();
        extract($eModels_view_data);

        if (request()->ajax()) {
            return view('PkgGapp::ePackage._edit', array_merge(compact('itemEPackage',),$eModel_compact_value));
        }

        return view('PkgGapp::ePackage.edit', array_merge(compact('itemEPackage',),$eModel_compact_value));


    }
    public function update(EPackageRequest $request, string $id) {

        $validatedData = $request->validated();
        $ePackage = $this->ePackageService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $ePackage,
                'modelName' =>  __('PkgGapp::ePackage.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $ePackage->id]
            );
        }

        return redirect()->route('ePackages.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $ePackage,
                'modelName' =>  __('PkgGapp::ePackage.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $ePackage = $this->ePackageService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $ePackage,
                'modelName' =>  __('PkgGapp::ePackage.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('ePackages.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $ePackage,
                'modelName' =>  __('PkgGapp::ePackage.singular')
                ])
        );

    }

    public function export($format)
    {
        $ePackages_data = $this->ePackageService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EPackageExport($ePackages_data,'csv'), 'ePackage_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EPackageExport($ePackages_data,'xlsx'), 'ePackage_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EPackageImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('ePackages.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('ePackages.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::ePackage.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEPackages()
    {
        $ePackages = $this->ePackageService->all();
        return response()->json($ePackages);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $ePackage = $this->ePackageService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEPackage = $this->ePackageService->dataCalcul($ePackage);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedEPackage
        ]);
    }
    

}