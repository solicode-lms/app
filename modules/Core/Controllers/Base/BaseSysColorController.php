<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\SysColorService;
use Modules\PkgGestionTaches\Services\EtatRealisationTacheService;
use Modules\Core\Services\SysModelService;
use Modules\PkgGestionTaches\Services\LabelRealisationTacheService;
use Modules\Core\Services\SysModuleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\Core\App\Requests\SysColorRequest;
use Modules\Core\Models\SysColor;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\SysColorExport;
use Modules\Core\App\Imports\SysColorImport;
use Modules\Core\Services\ContextState;

class BaseSysColorController extends AdminController
{
    protected $sysColorService;

    public function __construct(SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $sysColorService;
        $this->sysColorService = $sysColorService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('sysColor.index');



        // Extraire les paramètres de recherche, page, et filtres
        $sysColors_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('sysColors_search', $this->viewState->get("filter.sysColor.sysColors_search"))],
            $request->except(['sysColors_search', 'page', 'sort'])
        );

        // Paginer les sysColors
        $sysColors_data = $this->sysColorService->paginate($sysColors_params);

        // Récupérer les statistiques et les champs filtrables
        $sysColors_stats = $this->sysColorService->getsysColorStats();
        $this->viewState->set('stats.sysColor.stats'  , $sysColors_stats);
        $sysColors_filters = $this->sysColorService->getFieldsFilterable();
        $sysColor_instance =  $this->sysColorService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('Core::sysColor._table', compact('sysColors_data', 'sysColors_stats', 'sysColors_filters','sysColor_instance'))->render();
        }

        return view('Core::sysColor.index', compact('sysColors_data', 'sysColors_stats', 'sysColors_filters','sysColor_instance'));
    }
    public function create() {


        $itemSysColor = $this->sysColorService->createInstance();
        


        if (request()->ajax()) {
            return view('Core::sysColor._fields', compact('itemSysColor'));
        }
        return view('Core::sysColor.create', compact('itemSysColor'));
    }
    public function store(SysColorRequest $request) {
        $validatedData = $request->validated();
        $sysColor = $this->sysColorService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $sysColor,
                'modelName' => __('Core::sysColor.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $sysColor->id]
            );
        }

        return redirect()->route('sysColors.edit',['sysColor' => $sysColor->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sysColor,
                'modelName' => __('Core::sysColor.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('sysColor.edit_' . $id);


        $itemSysColor = $this->sysColorService->find($id);




        $this->viewState->set('scope.etatRealisationTache.sys_color_id', $id);


        $etatRealisationTacheService =  new EtatRealisationTacheService();
        $etatRealisationTaches_data =  $etatRealisationTacheService->paginate();
        $etatRealisationTaches_stats = $etatRealisationTacheService->getetatRealisationTacheStats();
        $etatRealisationTaches_filters = $etatRealisationTacheService->getFieldsFilterable();
        $etatRealisationTache_instance =  $etatRealisationTacheService->createInstance();

        $this->viewState->set('scope.sysModel.sys_color_id', $id);


        $sysModelService =  new SysModelService();
        $sysModels_data =  $sysModelService->paginate();
        $sysModels_stats = $sysModelService->getsysModelStats();
        $sysModels_filters = $sysModelService->getFieldsFilterable();
        $sysModel_instance =  $sysModelService->createInstance();

        $this->viewState->set('scope.labelRealisationTache.sys_color_id', $id);


        $labelRealisationTacheService =  new LabelRealisationTacheService();
        $labelRealisationTaches_data =  $labelRealisationTacheService->paginate();
        $labelRealisationTaches_stats = $labelRealisationTacheService->getlabelRealisationTacheStats();
        $labelRealisationTaches_filters = $labelRealisationTacheService->getFieldsFilterable();
        $labelRealisationTache_instance =  $labelRealisationTacheService->createInstance();

        $this->viewState->set('scope.sysModule.sys_color_id', $id);


        $sysModuleService =  new SysModuleService();
        $sysModules_data =  $sysModuleService->paginate();
        $sysModules_stats = $sysModuleService->getsysModuleStats();
        $sysModules_filters = $sysModuleService->getFieldsFilterable();
        $sysModule_instance =  $sysModuleService->createInstance();

        if (request()->ajax()) {
            return view('Core::sysColor._edit', compact('itemSysColor', 'etatRealisationTaches_data', 'sysModels_data', 'labelRealisationTaches_data', 'sysModules_data', 'etatRealisationTaches_stats', 'sysModels_stats', 'labelRealisationTaches_stats', 'sysModules_stats', 'etatRealisationTaches_filters', 'sysModels_filters', 'labelRealisationTaches_filters', 'sysModules_filters', 'etatRealisationTache_instance', 'sysModel_instance', 'labelRealisationTache_instance', 'sysModule_instance'));
        }

        return view('Core::sysColor.edit', compact('itemSysColor', 'etatRealisationTaches_data', 'sysModels_data', 'labelRealisationTaches_data', 'sysModules_data', 'etatRealisationTaches_stats', 'sysModels_stats', 'labelRealisationTaches_stats', 'sysModules_stats', 'etatRealisationTaches_filters', 'sysModels_filters', 'labelRealisationTaches_filters', 'sysModules_filters', 'etatRealisationTache_instance', 'sysModel_instance', 'labelRealisationTache_instance', 'sysModule_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('sysColor.edit_' . $id);


        $itemSysColor = $this->sysColorService->find($id);




        $this->viewState->set('scope.etatRealisationTache.sys_color_id', $id);
        

        $etatRealisationTacheService =  new EtatRealisationTacheService();
        $etatRealisationTaches_data =  $etatRealisationTacheService->paginate();
        $etatRealisationTaches_stats = $etatRealisationTacheService->getetatRealisationTacheStats();
        $this->viewState->set('stats.etatRealisationTache.stats'  , $etatRealisationTaches_stats);
        $etatRealisationTaches_filters = $etatRealisationTacheService->getFieldsFilterable();
        $etatRealisationTache_instance =  $etatRealisationTacheService->createInstance();

        $this->viewState->set('scope.sysModel.sys_color_id', $id);
        

        $sysModelService =  new SysModelService();
        $sysModels_data =  $sysModelService->paginate();
        $sysModels_stats = $sysModelService->getsysModelStats();
        $this->viewState->set('stats.sysModel.stats'  , $sysModels_stats);
        $sysModels_filters = $sysModelService->getFieldsFilterable();
        $sysModel_instance =  $sysModelService->createInstance();

        $this->viewState->set('scope.labelRealisationTache.sys_color_id', $id);
        

        $labelRealisationTacheService =  new LabelRealisationTacheService();
        $labelRealisationTaches_data =  $labelRealisationTacheService->paginate();
        $labelRealisationTaches_stats = $labelRealisationTacheService->getlabelRealisationTacheStats();
        $this->viewState->set('stats.labelRealisationTache.stats'  , $labelRealisationTaches_stats);
        $labelRealisationTaches_filters = $labelRealisationTacheService->getFieldsFilterable();
        $labelRealisationTache_instance =  $labelRealisationTacheService->createInstance();

        $this->viewState->set('scope.sysModule.sys_color_id', $id);
        

        $sysModuleService =  new SysModuleService();
        $sysModules_data =  $sysModuleService->paginate();
        $sysModules_stats = $sysModuleService->getsysModuleStats();
        $this->viewState->set('stats.sysModule.stats'  , $sysModules_stats);
        $sysModules_filters = $sysModuleService->getFieldsFilterable();
        $sysModule_instance =  $sysModuleService->createInstance();

        if (request()->ajax()) {
            return view('Core::sysColor._edit', compact('itemSysColor', 'etatRealisationTaches_data', 'sysModels_data', 'labelRealisationTaches_data', 'sysModules_data', 'etatRealisationTaches_stats', 'sysModels_stats', 'labelRealisationTaches_stats', 'sysModules_stats', 'etatRealisationTaches_filters', 'sysModels_filters', 'labelRealisationTaches_filters', 'sysModules_filters', 'etatRealisationTache_instance', 'sysModel_instance', 'labelRealisationTache_instance', 'sysModule_instance'));
        }

        return view('Core::sysColor.edit', compact('itemSysColor', 'etatRealisationTaches_data', 'sysModels_data', 'labelRealisationTaches_data', 'sysModules_data', 'etatRealisationTaches_stats', 'sysModels_stats', 'labelRealisationTaches_stats', 'sysModules_stats', 'etatRealisationTaches_filters', 'sysModels_filters', 'labelRealisationTaches_filters', 'sysModules_filters', 'etatRealisationTache_instance', 'sysModel_instance', 'labelRealisationTache_instance', 'sysModule_instance'));

    }
    public function update(SysColorRequest $request, string $id) {

        $validatedData = $request->validated();
        $sysColor = $this->sysColorService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $sysColor,
                'modelName' =>  __('Core::sysColor.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $sysColor->id]
            );
        }

        return redirect()->route('sysColors.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysColor,
                'modelName' =>  __('Core::sysColor.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $sysColor = $this->sysColorService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $sysColor,
                'modelName' =>  __('Core::sysColor.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('sysColors.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysColor,
                'modelName' =>  __('Core::sysColor.singular')
                ])
        );

    }

    public function export($format)
    {
        $sysColors_data = $this->sysColorService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new SysColorExport($sysColors_data,'csv'), 'sysColor_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new SysColorExport($sysColors_data,'xlsx'), 'sysColor_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new SysColorImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sysColors.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sysColors.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::sysColor.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysColors()
    {
        $sysColors = $this->sysColorService->all();
        return response()->json($sysColors);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $sysColor = $this->sysColorService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedSysColor = $this->sysColorService->dataCalcul($sysColor);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedSysColor
        ]);
    }
    

}
