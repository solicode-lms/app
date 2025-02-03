<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\FeatureService;
use Modules\PkgAutorisation\Services\PermissionService;
use Modules\Core\Services\FeatureDomainService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\FeatureRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\FeatureExport;
use Modules\Core\App\Imports\FeatureImport;
use Modules\Core\Services\ContextState;

class BaseFeatureController extends AdminController
{
    protected $featureService;
    protected $permissionService;
    protected $featureDomainService;

    public function __construct(FeatureService $featureService, PermissionService $permissionService, FeatureDomainService $featureDomainService) {
        parent::__construct();
        $this->featureService = $featureService;
        $this->permissionService = $permissionService;
        $this->featureDomainService = $featureDomainService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $features_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('features_search', '')],
            $request->except(['features_search', 'page', 'sort'])
        );

        // Paginer les features
        $features_data = $this->featureService->paginate($features_params);

        // Récupérer les statistiques et les champs filtrables
        $features_stats = $this->featureService->getfeatureStats();
        $features_filters = $this->featureService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('Core::feature._table', compact('features_data', 'features_stats', 'features_filters'))->render();
        }

        return view('Core::feature.index', compact('features_data', 'features_stats', 'features_filters'));
    }
    public function create() {
        $itemFeature = $this->featureService->createInstance();
        $permissions = $this->permissionService->all();
        $featureDomains = $this->featureDomainService->all();


        if (request()->ajax()) {
            return view('Core::feature._fields', compact('itemFeature', 'permissions', 'featureDomains'));
        }
        return view('Core::feature.create', compact('itemFeature', 'permissions', 'featureDomains'));
    }
    public function store(FeatureRequest $request) {
        $validatedData = $request->validated();
        $feature = $this->featureService->create($validatedData);


        if ($request->has('permissions')) {
            $feature->permissions()->sync($request->input('permissions'));
        }


        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'feature_id' => $feature->id,
            'message' => 
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
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('feature_id', $id);
        
        $itemFeature = $this->featureService->find($id);
        $permissions = $this->permissionService->all();
        $featureDomains = $this->featureDomainService->all();

        if (request()->ajax()) {
            return view('Core::feature._fields', compact('itemFeature', 'permissions', 'featureDomains'));
        }

        return view('Core::feature.edit', compact('itemFeature', 'permissions', 'featureDomains'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('feature_id', $id);
        
        $itemFeature = $this->featureService->find($id);
        $permissions = $this->permissionService->all();
        $featureDomains = $this->featureDomainService->all();

        if (request()->ajax()) {
            return view('Core::feature._fields', compact('itemFeature', 'permissions', 'featureDomains'));
        }

        return view('Core::feature.edit', compact('itemFeature', 'permissions', 'featureDomains'));

    }
    public function update(FeatureRequest $request, string $id) {

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
    public function destroy(Request $request, string $id) {

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
        $features_data = $this->featureService->all();
        return Excel::download(new FeatureExport($features_data), 'feature_export.xlsx');
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
