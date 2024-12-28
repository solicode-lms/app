<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\FeatureRequest;
use Modules\Core\Services\FeatureService;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\Core\Services\FeatureDomainService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\FeatureExport;
use Modules\Core\App\Imports\FeatureImport;

class FeatureController extends AdminController
{
    protected $featureService;
    protected $permissionService;
    protected $featureDomainService;

    public function __construct(FeatureService $featureService, PermissionService $permissionService, FeatureDomainService $featureDomainService)
    {
        parent::__construct();
        $this->featureService = $featureService;
        $this->permissionService = $permissionService;
        $this->featureDomainService = $featureDomainService;
    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $data = $this->featureService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('Core::feature._table', compact('data'))->render();
        }

        return view('Core::feature.index', compact('data','searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemFeature = $this->featureService->createInstance();
        $permissions = $this->permissionService->all();
        $featureDomains = $this->featureDomainService->all();

        if (request()->ajax()) {
            return view('Core::feature._fields', compact('itemFeature', 'permissions', 'featureDomains'));
        }
        return view('Core::feature.create', compact('itemFeature', 'permissions', 'featureDomains'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(FeatureRequest $request)
    {
        $validatedData = $request->validated();
        $feature = $this->featureService->create($validatedData);

        if ($request->has('permissions')) {
            $feature->permissions()->sync($request->input('permissions'));
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $feature,
                'modelName' => __('Core::feature.singular')])
            ]);
        }

        return redirect()->route('features.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $feature,
                'modelName' => __('Core::feature.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemFeature = $this->featureService->find($id);
        $permissions = $this->permissionService->all();
        $featureDomains = $this->featureDomainService->all();

        if (request()->ajax()) {
            return view('Core::feature._fields', compact('itemFeature', 'permissions', 'featureDomains'));
        }

        return view('Core::feature.show', compact('itemFeature'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemFeature = $this->featureService->find($id);
        $permissions = $this->permissionService->all();
        $featureDomains = $this->featureDomainService->all();

        if (request()->ajax()) {
            return view('Core::feature._fields', compact('itemFeature', 'permissions', 'featureDomains'));
        }

        return view('Core::feature.edit', compact('itemFeature', 'permissions', 'featureDomains'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(FeatureRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $feature = $this->featureService->update($id, $validatedData);

        $feature->permissions()->sync($request->input('permissions'));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $feature,
                'modelName' =>  __('Core::feature.singular')])
            ]);
        }

        return redirect()->route('features.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $feature,
                'modelName' =>  __('Core::feature.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $feature = $this->featureService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $feature,
                'modelName' =>  __('Core::feature.singular')])
            ]);
        }

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
