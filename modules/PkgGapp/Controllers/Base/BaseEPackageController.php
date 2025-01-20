<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;
use Modules\PkgGapp\Services\EPackageService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgGapp\App\Requests\EPackageRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\EPackageExport;
use Modules\PkgGapp\App\Imports\EPackageImport;
use Modules\Core\Services\ContextState;

class BaseEPackageController extends AdminController
{
    protected $ePackageService;

    public function __construct(EPackageService $ePackageService) {
        parent::__construct();
        $this->ePackageService = $ePackageService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $ePackages_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('ePackages_search', '')],
            $request->except(['ePackages_search', 'page', 'sort'])
        );

        // Paginer les ePackages
        $ePackages_data = $this->ePackageService->paginate($ePackages_params);

        // Récupérer les statistiques et les champs filtrables
        $ePackages_stats = $this->ePackageService->getePackageStats();
        $ePackages_filters = $this->ePackageService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::ePackage._table', compact('ePackages_data', 'ePackages_stats', 'ePackages_filters'))->render();
        }

        return view('PkgGapp::ePackage.index', compact('ePackages_data', 'ePackages_stats', 'ePackages_filters'));
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
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $ePackage,
                'modelName' => __('PkgGapp::ePackage.singular')])
            ]);
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
        $itemEPackage = $this->ePackageService->find($id);


        if (request()->ajax()) {
            return view('PkgGapp::ePackage._fields', compact('itemEPackage'));
        }

        return view('PkgGapp::ePackage.show', compact('itemEPackage'));

    }
    public function edit(string $id) {

        $itemEPackage = $this->ePackageService->find($id);
         $eModels_data =  $itemEPackage->eModels()->paginate(10);

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('ePackage_id', $id);


        if (request()->ajax()) {
            return view('PkgGapp::ePackage._fields', compact('itemEPackage', 'ePackages_data'));
        }

        return view('PkgGapp::ePackage.edit', compact('itemEPackage', 'ePackages_data'));

    }
    public function update(EPackageRequest $request, string $id) {

        $validatedData = $request->validated();
        $ePackage = $this->ePackageService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $ePackage,
                'modelName' =>  __('PkgGapp::ePackage.singular')])
            ]);
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
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $ePackage,
                'modelName' =>  __('PkgGapp::ePackage.singular')])
            ]);
        }

        return redirect()->route('ePackages.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $ePackage,
                'modelName' =>  __('PkgGapp::ePackage.singular')
                ])
        );

    }

    public function export()
    {
        $ePackages_data = $this->ePackageService->all();
        return Excel::download(new EPackageExport($ePackages_data), 'ePackage_export.xlsx');
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

}
