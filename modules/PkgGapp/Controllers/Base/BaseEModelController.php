<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EModelService;
use Modules\PkgGapp\Services\EPackageService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgGapp\App\Requests\EModelRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\EModelExport;
use Modules\PkgGapp\App\Imports\EModelImport;
use Modules\Core\Services\ContextState;

class BaseEModelController extends AdminController
{
    protected $eModelService;
    protected $ePackageService;

    public function __construct(EModelService $eModelService, EPackageService $ePackageService) {
        parent::__construct();
        $this->eModelService = $eModelService;
        $this->ePackageService = $ePackageService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $eModels_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('eModels_search', '')],
            $request->except(['eModels_search', 'page', 'sort'])
        );

        // Paginer les eModels
        $eModels_data = $this->eModelService->paginate($eModels_params);

        // Récupérer les statistiques et les champs filtrables
        $eModels_stats = $this->eModelService->geteModelStats();
        $eModels_filters = $this->eModelService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::eModel._table', compact('eModels_data', 'eModels_stats', 'eModels_filters'))->render();
        }

        return view('PkgGapp::eModel.index', compact('eModels_data', 'eModels_stats', 'eModels_filters'));
    }
    public function create() {
        $itemEModel = $this->eModelService->createInstance();
        $ePackages = $this->ePackageService->all();


        if (request()->ajax()) {
            return view('PkgGapp::eModel._fields', compact('itemEModel', 'ePackages'));
        }
        return view('PkgGapp::eModel.create', compact('itemEModel', 'ePackages'));
    }
    public function store(EModelRequest $request) {
        $validatedData = $request->validated();
        $eModel = $this->eModelService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $eModel,
                'modelName' => __('PkgGapp::eModel.singular')])
            ]);
        }

        return redirect()->route('eModels.edit',['eModel' => $eModel->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $eModel,
                'modelName' => __('PkgGapp::eModel.singular')
            ])
        );
    }
    public function show(string $id) {
        $itemEModel = $this->eModelService->find($id);
        $ePackages = $this->ePackageService->all();


        if (request()->ajax()) {
            return view('PkgGapp::eModel._fields', compact('itemEModel', 'ePackages'));
        }

        return view('PkgGapp::eModel.show', compact('itemEModel'));

    }
    public function edit(string $id) {

        $itemEModel = $this->eModelService->find($id);
        $ePackages = $this->ePackageService->all();
         $eDataFields_data =  $itemEModel->eDataFields()->paginate(10);
         $eRelationships_data =  $itemEModel->eRelationships()->paginate(10);

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('eModel_id', $id);


        if (request()->ajax()) {
            return view('PkgGapp::eModel._fields', compact('itemEModel', 'ePackages', 'eModels_data', 'eModels_data'));
        }

        return view('PkgGapp::eModel.edit', compact('itemEModel', 'ePackages', 'eModels_data', 'eModels_data'));

    }
    public function update(EModelRequest $request, string $id) {

        $validatedData = $request->validated();
        $eModel = $this->eModelService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $eModel,
                'modelName' =>  __('PkgGapp::eModel.singular')])
            ]);
        }

        return redirect()->route('eModels.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $eModel,
                'modelName' =>  __('PkgGapp::eModel.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $eModel = $this->eModelService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eModel,
                'modelName' =>  __('PkgGapp::eModel.singular')])
            ]);
        }

        return redirect()->route('eModels.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $eModel,
                'modelName' =>  __('PkgGapp::eModel.singular')
                ])
        );

    }

    public function export()
    {
        $eModels_data = $this->eModelService->all();
        return Excel::download(new EModelExport($eModels_data), 'eModel_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new EModelImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('eModels.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('eModels.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::eModel.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEModels()
    {
        $eModels = $this->eModelService->all();
        return response()->json($eModels);
    }

}
