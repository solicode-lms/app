<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers\Base;
use Modules\Core\Services\SysColorService;
use Modules\PkgAutoformation\Services\EtatChapitreService;
use Modules\PkgGestionTaches\Services\EtatRealisationTacheService;
use Modules\Core\Services\SysModelService;
use Modules\PkgAutoformation\Services\EtatFormationService;
use Modules\PkgGestionTaches\Services\LabelRealisationTacheService;
use Modules\Core\Services\SysModuleService;
use Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService;
use Modules\PkgWidgets\Services\SectionWidgetService;
use Modules\PkgWidgets\Services\WidgetService;
use Modules\PkgAutoformation\Services\WorkflowChapitreService;
use Modules\PkgAutoformation\Services\WorkflowFormationService;
use Modules\PkgRealisationProjets\Services\WorkflowProjetService;
use Modules\PkgGestionTaches\Services\WorkflowTacheService;
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

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('sysColor.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('sysColor');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $sysColors_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'sysColors_search',
                $this->viewState->get("filter.sysColor.sysColors_search")
            )],
            $request->except(['sysColors_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->sysColorService->prepareDataForIndexView($sysColors_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('Core::sysColor._index', $sysColor_compact_value)->render();
            }else{
                return view($sysColor_partialViewName, $sysColor_compact_value)->render();
            }
        }

        return view('Core::sysColor.index', $sysColor_compact_value);
    }
    /**
     */
    public function create() {


        $itemSysColor = $this->sysColorService->createInstance();
        


        if (request()->ajax()) {
            return view('Core::sysColor._fields', compact('itemSysColor'));
        }
        return view('Core::sysColor.create', compact('itemSysColor'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $sysColor_ids = $request->input('ids', []);

        if (!is_array($sysColor_ids) || count($sysColor_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemSysColor = $this->sysColorService->find($sysColor_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemSysColor = $this->sysColorService->createInstance();
        
        if (request()->ajax()) {
            return view('Core::sysColor._fields', compact('bulkEdit', 'sysColor_ids', 'itemSysColor'));
        }
        return view('Core::sysColor.bulk-edit', compact('bulkEdit', 'sysColor_ids', 'itemSysColor'));
    }
    /**
     */
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
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('sysColor.edit_' . $id);


        $itemSysColor = $this->sysColorService->edit($id);




        $this->viewState->set('scope.etatChapitre.sys_color_id', $id);
        

        $etatChapitreService =  new EtatChapitreService();
        $etatChapitres_view_data = $etatChapitreService->prepareDataForIndexView();
        extract($etatChapitres_view_data);

        $this->viewState->set('scope.etatRealisationTache.sys_color_id', $id);
        

        $etatRealisationTacheService =  new EtatRealisationTacheService();
        $etatRealisationTaches_view_data = $etatRealisationTacheService->prepareDataForIndexView();
        extract($etatRealisationTaches_view_data);

        $this->viewState->set('scope.sysModel.sys_color_id', $id);
        

        $sysModelService =  new SysModelService();
        $sysModels_view_data = $sysModelService->prepareDataForIndexView();
        extract($sysModels_view_data);

        $this->viewState->set('scope.etatFormation.sys_color_id', $id);
        

        $etatFormationService =  new EtatFormationService();
        $etatFormations_view_data = $etatFormationService->prepareDataForIndexView();
        extract($etatFormations_view_data);

        $this->viewState->set('scope.labelRealisationTache.sys_color_id', $id);
        

        $labelRealisationTacheService =  new LabelRealisationTacheService();
        $labelRealisationTaches_view_data = $labelRealisationTacheService->prepareDataForIndexView();
        extract($labelRealisationTaches_view_data);

        $this->viewState->set('scope.sysModule.sys_color_id', $id);
        

        $sysModuleService =  new SysModuleService();
        $sysModules_view_data = $sysModuleService->prepareDataForIndexView();
        extract($sysModules_view_data);

        $this->viewState->set('scope.etatsRealisationProjet.sys_color_id', $id);
        

        $etatsRealisationProjetService =  new EtatsRealisationProjetService();
        $etatsRealisationProjets_view_data = $etatsRealisationProjetService->prepareDataForIndexView();
        extract($etatsRealisationProjets_view_data);

        $this->viewState->set('scope.sectionWidget.sys_color_id', $id);
        

        $sectionWidgetService =  new SectionWidgetService();
        $sectionWidgets_view_data = $sectionWidgetService->prepareDataForIndexView();
        extract($sectionWidgets_view_data);

        $this->viewState->set('scope.widget.sys_color_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        $this->viewState->set('scope.workflowChapitre.sys_color_id', $id);
        

        $workflowChapitreService =  new WorkflowChapitreService();
        $workflowChapitres_view_data = $workflowChapitreService->prepareDataForIndexView();
        extract($workflowChapitres_view_data);

        $this->viewState->set('scope.workflowFormation.sys_color_id', $id);
        

        $workflowFormationService =  new WorkflowFormationService();
        $workflowFormations_view_data = $workflowFormationService->prepareDataForIndexView();
        extract($workflowFormations_view_data);

        $this->viewState->set('scope.workflowProjet.sys_color_id', $id);
        

        $workflowProjetService =  new WorkflowProjetService();
        $workflowProjets_view_data = $workflowProjetService->prepareDataForIndexView();
        extract($workflowProjets_view_data);

        $this->viewState->set('scope.workflowTache.sys_color_id', $id);
        

        $workflowTacheService =  new WorkflowTacheService();
        $workflowTaches_view_data = $workflowTacheService->prepareDataForIndexView();
        extract($workflowTaches_view_data);

        if (request()->ajax()) {
            return view('Core::sysColor._edit', array_merge(compact('itemSysColor',),$etatChapitre_compact_value, $etatRealisationTache_compact_value, $sysModel_compact_value, $etatFormation_compact_value, $labelRealisationTache_compact_value, $sysModule_compact_value, $etatsRealisationProjet_compact_value, $sectionWidget_compact_value, $widget_compact_value, $workflowChapitre_compact_value, $workflowFormation_compact_value, $workflowProjet_compact_value, $workflowTache_compact_value));
        }

        return view('Core::sysColor.edit', array_merge(compact('itemSysColor',),$etatChapitre_compact_value, $etatRealisationTache_compact_value, $sysModel_compact_value, $etatFormation_compact_value, $labelRealisationTache_compact_value, $sysModule_compact_value, $etatsRealisationProjet_compact_value, $sectionWidget_compact_value, $widget_compact_value, $workflowChapitre_compact_value, $workflowFormation_compact_value, $workflowProjet_compact_value, $workflowTache_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('sysColor.edit_' . $id);


        $itemSysColor = $this->sysColorService->edit($id);




        $this->viewState->set('scope.etatChapitre.sys_color_id', $id);
        

        $etatChapitreService =  new EtatChapitreService();
        $etatChapitres_view_data = $etatChapitreService->prepareDataForIndexView();
        extract($etatChapitres_view_data);

        $this->viewState->set('scope.etatRealisationTache.sys_color_id', $id);
        

        $etatRealisationTacheService =  new EtatRealisationTacheService();
        $etatRealisationTaches_view_data = $etatRealisationTacheService->prepareDataForIndexView();
        extract($etatRealisationTaches_view_data);

        $this->viewState->set('scope.sysModel.sys_color_id', $id);
        

        $sysModelService =  new SysModelService();
        $sysModels_view_data = $sysModelService->prepareDataForIndexView();
        extract($sysModels_view_data);

        $this->viewState->set('scope.etatFormation.sys_color_id', $id);
        

        $etatFormationService =  new EtatFormationService();
        $etatFormations_view_data = $etatFormationService->prepareDataForIndexView();
        extract($etatFormations_view_data);

        $this->viewState->set('scope.labelRealisationTache.sys_color_id', $id);
        

        $labelRealisationTacheService =  new LabelRealisationTacheService();
        $labelRealisationTaches_view_data = $labelRealisationTacheService->prepareDataForIndexView();
        extract($labelRealisationTaches_view_data);

        $this->viewState->set('scope.sysModule.sys_color_id', $id);
        

        $sysModuleService =  new SysModuleService();
        $sysModules_view_data = $sysModuleService->prepareDataForIndexView();
        extract($sysModules_view_data);

        $this->viewState->set('scope.etatsRealisationProjet.sys_color_id', $id);
        

        $etatsRealisationProjetService =  new EtatsRealisationProjetService();
        $etatsRealisationProjets_view_data = $etatsRealisationProjetService->prepareDataForIndexView();
        extract($etatsRealisationProjets_view_data);

        $this->viewState->set('scope.sectionWidget.sys_color_id', $id);
        

        $sectionWidgetService =  new SectionWidgetService();
        $sectionWidgets_view_data = $sectionWidgetService->prepareDataForIndexView();
        extract($sectionWidgets_view_data);

        $this->viewState->set('scope.widget.sys_color_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        $this->viewState->set('scope.workflowChapitre.sys_color_id', $id);
        

        $workflowChapitreService =  new WorkflowChapitreService();
        $workflowChapitres_view_data = $workflowChapitreService->prepareDataForIndexView();
        extract($workflowChapitres_view_data);

        $this->viewState->set('scope.workflowFormation.sys_color_id', $id);
        

        $workflowFormationService =  new WorkflowFormationService();
        $workflowFormations_view_data = $workflowFormationService->prepareDataForIndexView();
        extract($workflowFormations_view_data);

        $this->viewState->set('scope.workflowProjet.sys_color_id', $id);
        

        $workflowProjetService =  new WorkflowProjetService();
        $workflowProjets_view_data = $workflowProjetService->prepareDataForIndexView();
        extract($workflowProjets_view_data);

        $this->viewState->set('scope.workflowTache.sys_color_id', $id);
        

        $workflowTacheService =  new WorkflowTacheService();
        $workflowTaches_view_data = $workflowTacheService->prepareDataForIndexView();
        extract($workflowTaches_view_data);

        if (request()->ajax()) {
            return view('Core::sysColor._edit', array_merge(compact('itemSysColor',),$etatChapitre_compact_value, $etatRealisationTache_compact_value, $sysModel_compact_value, $etatFormation_compact_value, $labelRealisationTache_compact_value, $sysModule_compact_value, $etatsRealisationProjet_compact_value, $sectionWidget_compact_value, $widget_compact_value, $workflowChapitre_compact_value, $workflowFormation_compact_value, $workflowProjet_compact_value, $workflowTache_compact_value));
        }

        return view('Core::sysColor.edit', array_merge(compact('itemSysColor',),$etatChapitre_compact_value, $etatRealisationTache_compact_value, $sysModel_compact_value, $etatFormation_compact_value, $labelRealisationTache_compact_value, $sysModule_compact_value, $etatsRealisationProjet_compact_value, $sectionWidget_compact_value, $widget_compact_value, $workflowChapitre_compact_value, $workflowFormation_compact_value, $workflowProjet_compact_value, $workflowTache_compact_value));


    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $sysColor_ids = $request->input('sysColor_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($sysColor_ids) || count($sysColor_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($sysColor_ids as $id) {
            $entity = $this->sysColorService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->sysColorService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->sysColorService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $sysColor_ids = $request->input('ids', []);
        if (!is_array($sysColor_ids) || count($sysColor_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($sysColor_ids as $id) {
            $entity = $this->sysColorService->find($id);
            $this->sysColorService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($sysColor_ids) . ' éléments',
            'modelName' => __('Core::sysColor.plural')
        ]));
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