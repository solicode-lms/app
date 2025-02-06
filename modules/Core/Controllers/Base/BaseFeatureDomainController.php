<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\FeatureDomainService;
use Modules\Core\Services\SysModuleService;
use Modules\Core\Services\FeatureService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\FeatureDomainRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\FeatureDomainExport;
use Modules\Core\App\Imports\FeatureDomainImport;
use Modules\Core\Services\ContextState;

class BaseFeatureDomainController extends AdminController
{
    protected $featureDomainService;
    protected $sysModuleService;

    public function __construct(FeatureDomainService $featureDomainService, SysModuleService $sysModuleService) {
        parent::__construct();
        $this->featureDomainService = $featureDomainService;
        $this->sysModuleService = $sysModuleService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $featureDomains_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('featureDomains_search', '')],
            $request->except(['featureDomains_search', 'page', 'sort'])
        );

        // Paginer les featureDomains
        $featureDomains_data = $this->featureDomainService->paginate($featureDomains_params);

        // Récupérer les statistiques et les champs filtrables
        $featureDomains_stats = $this->featureDomainService->getfeatureDomainStats();
        $featureDomains_filters = $this->featureDomainService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('Core::featureDomain._table', compact('featureDomains_data', 'featureDomains_stats', 'featureDomains_filters'))->render();
        }

        return view('Core::featureDomain.index', compact('featureDomains_data', 'featureDomains_stats', 'featureDomains_filters'));
    }
    public function create() {
        $itemFeatureDomain = $this->featureDomainService->createInstance();
        $sysModules = $this->sysModuleService->all();


        if (request()->ajax()) {
            return view('Core::featureDomain._fields', compact('itemFeatureDomain', 'sysModules'));
        }
        return view('Core::featureDomain.create', compact('itemFeatureDomain', 'sysModules'));
    }
    public function store(FeatureDomainRequest $request) {
        $validatedData = $request->validated();
        $featureDomain = $this->featureDomainService->create($validatedData);

        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'entity_id' => $featureDomain->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $featureDomain,
                'modelName' => __('Core::featureDomain.singular')])
            ]);
        }

        return redirect()->route('featureDomains.edit',['featureDomain' => $featureDomain->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $featureDomain,
                'modelName' => __('Core::featureDomain.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('feature_domain_id', $id);
        
        $itemFeatureDomain = $this->featureDomainService->find($id);
        $sysModules = $this->sysModuleService->all();
        $featureService =  new FeatureService();
        $features_data =  $itemFeatureDomain->features()->paginate(10);
        $features_stats = $featureService->getfeatureStats();
        $features_filters = $featureService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('Core::featureDomain._edit', compact('itemFeatureDomain', 'sysModules', 'features_data', 'features_stats', 'features_filters'));
        }

        return view('Core::featureDomain.edit', compact('itemFeatureDomain', 'sysModules', 'features_data', 'features_stats', 'features_filters'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('feature_domain_id', $id);
        
        $itemFeatureDomain = $this->featureDomainService->find($id);
        $sysModules = $this->sysModuleService->all();
        $featureService =  new FeatureService();
        $features_data =  $itemFeatureDomain->features()->paginate(10);
        $features_stats = $featureService->getfeatureStats();
        $features_filters = $featureService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('Core::featureDomain._edit', compact('itemFeatureDomain', 'sysModules', 'features_data', 'features_stats', 'features_filters'));
        }

        return view('Core::featureDomain.edit', compact('itemFeatureDomain', 'sysModules', 'features_data', 'features_stats', 'features_filters'));

    }
    public function update(FeatureDomainRequest $request, string $id) {

        $validatedData = $request->validated();
        $featureDomain = $this->featureDomainService->update($id, $validatedData);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $featureDomain,
                'modelName' =>  __('Core::featureDomain.singular')])
            ]);
        }

        return redirect()->route('featureDomains.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $featureDomain,
                'modelName' =>  __('Core::featureDomain.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $featureDomain = $this->featureDomainService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $featureDomain,
                'modelName' =>  __('Core::featureDomain.singular')])
            ]);
        }

        return redirect()->route('featureDomains.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $featureDomain,
                'modelName' =>  __('Core::featureDomain.singular')
                ])
        );

    }

    public function export($format)
    {
        $featureDomains_data = $this->featureDomainService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new FeatureDomainExport($featureDomains_data,'csv'), 'featureDomain_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new FeatureDomainExport($featureDomains_data,'xlsx'), 'featureDomain_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new FeatureDomainImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('featureDomains.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('featureDomains.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::featureDomain.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFeatureDomains()
    {
        $featureDomains = $this->featureDomainService->all();
        return response()->json($featureDomains);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $featureDomain = $this->featureDomainService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedFeatureDomain = $this->featureDomainService->dataCalcul($featureDomain);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedFeatureDomain
        ]);
    }
    


}
