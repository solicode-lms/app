<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Controllers\Base;
use Modules\PkgAutoformation\Services\EtatChapitreService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\Core\Services\SysColorService;
use Modules\PkgAutoformation\Services\WorkflowChapitreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutoformation\App\Requests\EtatChapitreRequest;
use Modules\PkgAutoformation\Models\EtatChapitre;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutoformation\App\Exports\EtatChapitreExport;
use Modules\PkgAutoformation\App\Imports\EtatChapitreImport;
use Modules\Core\Services\ContextState;

class BaseEtatChapitreController extends AdminController
{
    protected $etatChapitreService;
    protected $formateurService;
    protected $sysColorService;
    protected $workflowChapitreService;

    public function __construct(EtatChapitreService $etatChapitreService, FormateurService $formateurService, SysColorService $sysColorService, WorkflowChapitreService $workflowChapitreService) {
        parent::__construct();
        $this->service  =  $etatChapitreService;
        $this->etatChapitreService = $etatChapitreService;
        $this->formateurService = $formateurService;
        $this->sysColorService = $sysColorService;
        $this->workflowChapitreService = $workflowChapitreService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('etatChapitre.index');
        
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.etatChapitre.formateur_id') == null){
           $this->viewState->init('scope.etatChapitre.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $etatChapitres_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'etatChapitres_search',
                $this->viewState->get("filter.etatChapitre.etatChapitres_search")
            )],
            $request->except(['etatChapitres_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatChapitreService->prepareDataForIndexView($etatChapitres_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgAutoformation::etatChapitre._index', $etatChapitre_compact_value)->render();
            }else{
                return view($etatChapitre_partialViewName, $etatChapitre_compact_value)->render();
            }
        }

        return view('PkgAutoformation::etatChapitre.index', $etatChapitre_compact_value);
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.etatChapitre.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemEtatChapitre = $this->etatChapitreService->createInstance();
        

        $workflowChapitres = $this->workflowChapitreService->all();
        $sysColors = $this->sysColorService->all();
        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgAutoformation::etatChapitre._fields', compact('itemEtatChapitre', 'formateurs', 'sysColors', 'workflowChapitres'));
        }
        return view('PkgAutoformation::etatChapitre.create', compact('itemEtatChapitre', 'formateurs', 'sysColors', 'workflowChapitres'));
    }
    public function store(EtatChapitreRequest $request) {
        $validatedData = $request->validated();
        $etatChapitre = $this->etatChapitreService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatChapitre,
                'modelName' => __('PkgAutoformation::etatChapitre.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $etatChapitre->id]
            );
        }

        return redirect()->route('etatChapitres.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatChapitre,
                'modelName' => __('PkgAutoformation::etatChapitre.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('etatChapitre.edit_' . $id);


        $itemEtatChapitre = $this->etatChapitreService->edit($id);
        $this->authorize('view', $itemEtatChapitre);


        $workflowChapitres = $this->workflowChapitreService->all();
        $sysColors = $this->sysColorService->all();
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgAutoformation::etatChapitre._fields', array_merge(compact('itemEtatChapitre','formateurs', 'sysColors', 'workflowChapitres'),));
        }

        return view('PkgAutoformation::etatChapitre.edit', array_merge(compact('itemEtatChapitre','formateurs', 'sysColors', 'workflowChapitres'),));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('etatChapitre.edit_' . $id);


        $itemEtatChapitre = $this->etatChapitreService->edit($id);
        $this->authorize('edit', $itemEtatChapitre);


        $workflowChapitres = $this->workflowChapitreService->all();
        $sysColors = $this->sysColorService->all();
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgAutoformation::etatChapitre._fields', array_merge(compact('itemEtatChapitre','formateurs', 'sysColors', 'workflowChapitres'),));
        }

        return view('PkgAutoformation::etatChapitre.edit', array_merge(compact('itemEtatChapitre','formateurs', 'sysColors', 'workflowChapitres'),));


    }
    public function update(EtatChapitreRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $etatChapitre = $this->etatChapitreService->find($id);
        $this->authorize('update', $etatChapitre);

        $validatedData = $request->validated();
        $etatChapitre = $this->etatChapitreService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatChapitre,
                'modelName' =>  __('PkgAutoformation::etatChapitre.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $etatChapitre->id]
            );
        }

        return redirect()->route('etatChapitres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatChapitre,
                'modelName' =>  __('PkgAutoformation::etatChapitre.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $etatChapitre = $this->etatChapitreService->find($id);
        $this->authorize('delete', $etatChapitre);

        $etatChapitre = $this->etatChapitreService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatChapitre,
                'modelName' =>  __('PkgAutoformation::etatChapitre.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('etatChapitres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatChapitre,
                'modelName' =>  __('PkgAutoformation::etatChapitre.singular')
                ])
        );

    }

    public function export($format)
    {
        $etatChapitres_data = $this->etatChapitreService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatChapitreExport($etatChapitres_data,'csv'), 'etatChapitre_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatChapitreExport($etatChapitres_data,'xlsx'), 'etatChapitre_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatChapitreImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatChapitres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatChapitres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutoformation::etatChapitre.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatChapitres()
    {
        $etatChapitres = $this->etatChapitreService->all();
        return response()->json($etatChapitres);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $etatChapitre = $this->etatChapitreService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEtatChapitre = $this->etatChapitreService->dataCalcul($etatChapitre);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedEtatChapitre
        ]);
    }
    

}