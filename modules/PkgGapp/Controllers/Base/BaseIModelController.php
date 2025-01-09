<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgGapp\App\Requests\IModelRequest;
use Modules\PkgGapp\Services\IModelService;
use Modules\PkgGapp\Services\IPackageService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\IModelExport;
use Modules\PkgGapp\App\Imports\IModelImport;
use Modules\Core\Services\ContextState;

class BaseIModelController extends AdminController
{
    protected $iModelService;
    protected $iPackageService;

    public function __construct(IModelService $iModelService, IPackageService $iPackageService)
    {
        parent::__construct();
        $this->iModelService = $iModelService;
        $this->iPackageService = $iPackageService;

    }


    public function index(Request $request)
    {
        // Extraire les paramètres de recherche, page, et filtres
        $iModels_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('iModels_search', '')],
            $request->except(['iModels_search', 'page', 'sort'])
        );
    
        // Paginer les iModels
        $iModels_data = $this->iModelService->paginate($iModels_params);
    
        // Récupérer les statistiques et les champs filtrables
        $iModels_stats = $this->iModelService->getiModelStats();
        $iModels_filters = $this->iModelService->getFieldsFilterable();
    
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::iModel._table', compact('iModels_data', 'iModels_stats', 'iModels_filters'))->render();
        }
    
        return view('PkgGapp::iModel.index', compact('iModels_data', 'iModels_stats', 'iModels_filters'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemIModel = $this->iModelService->createInstance();
        $iPackages = $this->iPackageService->all();


        if (request()->ajax()) {
            return view('PkgGapp::iModel._fields', compact('itemIModel', 'iPackages'));
        }
        return view('PkgGapp::iModel.create', compact('itemIModel', 'iPackages'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(IModelRequest $request)
    {
        $validatedData = $request->validated();
        $iModel = $this->iModelService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $iModel,
                'modelName' => __('PkgGapp::iModel.singular')])
            ]);
        }

        return redirect()->route('iModels.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $iModel,
                'modelName' => __('PkgGapp::iModel.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemIModel = $this->iModelService->find($id);
        $iPackages = $this->iPackageService->all();


        if (request()->ajax()) {
            return view('PkgGapp::iModel._fields', compact('itemIModel', 'iPackages'));
        }

        return view('PkgGapp::iModel.show', compact('itemIModel'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {

        $itemIModel = $this->iModelService->find($id);
        $iPackages = $this->iPackageService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('iModel_id', $id);


        if (request()->ajax()) {
            return view('PkgGapp::iModel._fields', compact('itemIModel', 'iPackages'));
        }

        return view('PkgGapp::iModel.edit', compact('itemIModel', 'iPackages'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(IModelRequest $request, string $id)
    {

        $validatedData = $request->validated();
        $iModel = $this->iModelService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $iModel,
                'modelName' =>  __('PkgGapp::iModel.singular')])
            ]);
        }

        return redirect()->route('iModels.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $iModel,
                'modelName' =>  __('PkgGapp::iModel.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {

        $iModel = $this->iModelService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $iModel,
                'modelName' =>  __('PkgGapp::iModel.singular')])
            ]);
        }

        return redirect()->route('iModels.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $iModel,
                'modelName' =>  __('PkgGapp::iModel.singular')
                ])
        );
    }

    public function export()
    {
        $iModels_data = $this->iModelService->all();
        return Excel::download(new IModelExport($iModels_data), 'iModel_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new IModelImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('iModels.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('iModels.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::iModel.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getIModels()
    {
        $iModels = $this->iModelService->all();
        return response()->json($iModels);
    }
}
