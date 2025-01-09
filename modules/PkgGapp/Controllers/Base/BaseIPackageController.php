<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGapp\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgGapp\App\Requests\IPackageRequest;
use Modules\PkgGapp\Services\IPackageService;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGapp\App\Exports\IPackageExport;
use Modules\PkgGapp\App\Imports\IPackageImport;
use Modules\Core\Services\ContextState;

class BaseIPackageController extends AdminController
{
    protected $iPackageService;

    public function __construct(IPackageService $iPackageService)
    {
        parent::__construct();
        $this->iPackageService = $iPackageService;

    }


    public function index(Request $request)
    {
        // Extraire les paramètres de recherche, page, et filtres
        $iPackages_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('iPackages_search', '')],
            $request->except(['iPackages_search', 'page', 'sort'])
        );
    
        // Paginer les iPackages
        $iPackages_data = $this->iPackageService->paginate($iPackages_params);
    
        // Récupérer les statistiques et les champs filtrables
        $iPackages_stats = $this->iPackageService->getiPackageStats();
        $iPackages_filters = $this->iPackageService->getFieldsFilterable();
    
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgGapp::iPackage._table', compact('iPackages_data', 'iPackages_stats', 'iPackages_filters'))->render();
        }
    
        return view('PkgGapp::iPackage.index', compact('iPackages_data', 'iPackages_stats', 'iPackages_filters'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemIPackage = $this->iPackageService->createInstance();


        if (request()->ajax()) {
            return view('PkgGapp::iPackage._fields', compact('itemIPackage'));
        }
        return view('PkgGapp::iPackage.create', compact('itemIPackage'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(IPackageRequest $request)
    {
        $validatedData = $request->validated();
        $iPackage = $this->iPackageService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $iPackage,
                'modelName' => __('PkgGapp::iPackage.singular')])
            ]);
        }

        return redirect()->route('iPackages.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $iPackage,
                'modelName' => __('PkgGapp::iPackage.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemIPackage = $this->iPackageService->find($id);


        if (request()->ajax()) {
            return view('PkgGapp::iPackage._fields', compact('itemIPackage'));
        }

        return view('PkgGapp::iPackage.show', compact('itemIPackage'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {

        $itemIPackage = $this->iPackageService->find($id);

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('iPackage_id', $id);


        if (request()->ajax()) {
            return view('PkgGapp::iPackage._fields', compact('itemIPackage'));
        }

        return view('PkgGapp::iPackage.edit', compact('itemIPackage'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(IPackageRequest $request, string $id)
    {

        $validatedData = $request->validated();
        $iPackage = $this->iPackageService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $iPackage,
                'modelName' =>  __('PkgGapp::iPackage.singular')])
            ]);
        }

        return redirect()->route('iPackages.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $iPackage,
                'modelName' =>  __('PkgGapp::iPackage.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {

        $iPackage = $this->iPackageService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $iPackage,
                'modelName' =>  __('PkgGapp::iPackage.singular')])
            ]);
        }

        return redirect()->route('iPackages.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $iPackage,
                'modelName' =>  __('PkgGapp::iPackage.singular')
                ])
        );
    }

    public function export()
    {
        $iPackages_data = $this->iPackageService->all();
        return Excel::download(new IPackageExport($iPackages_data), 'iPackage_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new IPackageImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('iPackages.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('iPackages.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGapp::iPackage.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getIPackages()
    {
        $iPackages = $this->iPackageService->all();
        return response()->json($iPackages);
    }
}
