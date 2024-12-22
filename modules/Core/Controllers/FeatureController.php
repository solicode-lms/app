<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\FeatureRequest;
use Modules\Core\Services\FeatureService;
use Modules\Core\Services\PermissionService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\FeatureExport;
use Modules\Core\App\Imports\FeatureImport;

class FeatureController extends AdminController
{
    protected $featureService;
    protected $permissionService;

    public function __construct(FeatureService $featureService, PermissionService $permissionService)
    {
        parent::__construct();
        $this->featureService = $featureService;
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->featureService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('Core::feature._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('Core::feature.index', compact('data'));
    }

    public function create()
    {
        $item = $this->featureService->createInstance();
        $permissions = $this->permissionService->all();
        return view('Core::feature.create', compact('item', 'permissions'));
    }

    public function store(FeatureRequest $request)
    {
        $validatedData = $request->validated();
        $feature = $this->featureService->create($validatedData);

        if ($request->has('permissions')) {
            $feature->permissions()->sync($request->input('permissions'));
        }

        return redirect()->route('features.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $feature,
            'modelName' => __('Core::feature.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->featureService->find($id);
        return view('Core::feature.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->featureService->find($id);
        $permissions = $this->permissionService->all();
        return view('Core::feature.edit', compact('item', 'permissions'));
    }

    public function update(FeatureRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $feature = $this->featureService->update($id, $validatedData);


        if ($request->has('permissions')) {
            $feature->permissions()->sync($request->input('permissions'));
        }

        return redirect()->route('features.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $feature,
                'modelName' =>  __('Core::feature.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $feature = $this->featureService->destroy($id);
        return redirect()->route('features.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $feature,
                'modelName' =>  __('Core::feature.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->featureService->all();
        return Excel::download(new FeatureExport($data), 'feature_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new FeatureImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('features.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('features.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::feature.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFeatures()
    {
        $features = $this->featureService->all();
        return response()->json($features);
    }
}
