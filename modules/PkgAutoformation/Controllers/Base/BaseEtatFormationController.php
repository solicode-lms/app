<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Controllers\Base;
use Modules\PkgAutoformation\Services\EtatFormationService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\Core\Services\SysColorService;
use Modules\PkgAutoformation\Services\WorkflowFormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutoformation\App\Requests\EtatFormationRequest;
use Modules\PkgAutoformation\Models\EtatFormation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutoformation\App\Exports\EtatFormationExport;
use Modules\PkgAutoformation\App\Imports\EtatFormationImport;
use Modules\Core\Services\ContextState;

class BaseEtatFormationController extends AdminController
{
    protected $etatFormationService;
    protected $formateurService;
    protected $sysColorService;
    protected $workflowFormationService;

    public function __construct(EtatFormationService $etatFormationService, FormateurService $formateurService, SysColorService $sysColorService, WorkflowFormationService $workflowFormationService) {
        parent::__construct();
        $this->service  =  $etatFormationService;
        $this->etatFormationService = $etatFormationService;
        $this->formateurService = $formateurService;
        $this->sysColorService = $sysColorService;
        $this->workflowFormationService = $workflowFormationService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('etatFormation.index');
        
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.etatFormation.formateur_id') == null){
           $this->viewState->init('scope.etatFormation.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $etatFormations_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'etatFormations_search',
                $this->viewState->get("filter.etatFormation.etatFormations_search")
            )],
            $request->except(['etatFormations_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatFormationService->prepareDataForIndexView($etatFormations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($etatFormation_partialViewName, $etatFormation_compact_value)->render();
        }

        return view('PkgAutoformation::etatFormation.index', $etatFormation_compact_value);
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.etatFormation.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemEtatFormation = $this->etatFormationService->createInstance();
        

        $workflowFormations = $this->workflowFormationService->all();
        $sysColors = $this->sysColorService->all();
        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgAutoformation::etatFormation._fields', compact('itemEtatFormation', 'formateurs', 'sysColors', 'workflowFormations'));
        }
        return view('PkgAutoformation::etatFormation.create', compact('itemEtatFormation', 'formateurs', 'sysColors', 'workflowFormations'));
    }
    public function store(EtatFormationRequest $request) {
        $validatedData = $request->validated();
        $etatFormation = $this->etatFormationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatFormation,
                'modelName' => __('PkgAutoformation::etatFormation.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $etatFormation->id]
            );
        }

        return redirect()->route('etatFormations.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatFormation,
                'modelName' => __('PkgAutoformation::etatFormation.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('etatFormation.edit_' . $id);


        $itemEtatFormation = $this->etatFormationService->edit($id);
        $this->authorize('view', $itemEtatFormation);


        $workflowFormations = $this->workflowFormationService->all();
        $sysColors = $this->sysColorService->all();
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgAutoformation::etatFormation._fields', array_merge(compact('itemEtatFormation','formateurs', 'sysColors', 'workflowFormations'),));
        }

        return view('PkgAutoformation::etatFormation.edit', array_merge(compact('itemEtatFormation','formateurs', 'sysColors', 'workflowFormations'),));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('etatFormation.edit_' . $id);


        $itemEtatFormation = $this->etatFormationService->edit($id);
        $this->authorize('edit', $itemEtatFormation);


        $workflowFormations = $this->workflowFormationService->all();
        $sysColors = $this->sysColorService->all();
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgAutoformation::etatFormation._fields', array_merge(compact('itemEtatFormation','formateurs', 'sysColors', 'workflowFormations'),));
        }

        return view('PkgAutoformation::etatFormation.edit', array_merge(compact('itemEtatFormation','formateurs', 'sysColors', 'workflowFormations'),));


    }
    public function update(EtatFormationRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $etatFormation = $this->etatFormationService->find($id);
        $this->authorize('update', $etatFormation);

        $validatedData = $request->validated();
        $etatFormation = $this->etatFormationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatFormation,
                'modelName' =>  __('PkgAutoformation::etatFormation.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $etatFormation->id]
            );
        }

        return redirect()->route('etatFormations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatFormation,
                'modelName' =>  __('PkgAutoformation::etatFormation.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $etatFormation = $this->etatFormationService->find($id);
        $this->authorize('delete', $etatFormation);

        $etatFormation = $this->etatFormationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatFormation,
                'modelName' =>  __('PkgAutoformation::etatFormation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('etatFormations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatFormation,
                'modelName' =>  __('PkgAutoformation::etatFormation.singular')
                ])
        );

    }

    public function export($format)
    {
        $etatFormations_data = $this->etatFormationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatFormationExport($etatFormations_data,'csv'), 'etatFormation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatFormationExport($etatFormations_data,'xlsx'), 'etatFormation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatFormationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatFormations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatFormations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutoformation::etatFormation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatFormations()
    {
        $etatFormations = $this->etatFormationService->all();
        return response()->json($etatFormations);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $etatFormation = $this->etatFormationService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEtatFormation = $this->etatFormationService->dataCalcul($etatFormation);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedEtatFormation
        ]);
    }
    

}