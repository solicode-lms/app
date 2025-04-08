<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers\Base;
use Modules\PkgWidgets\Services\WidgetUtilisateurService;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgWidgets\Services\WidgetService;
use Modules\Core\Services\SysModuleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgWidgets\App\Requests\WidgetUtilisateurRequest;
use Modules\PkgWidgets\Models\WidgetUtilisateur;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgWidgets\App\Exports\WidgetUtilisateurExport;
use Modules\PkgWidgets\App\Imports\WidgetUtilisateurImport;
use Modules\Core\Services\ContextState;

class BaseWidgetUtilisateurController extends AdminController
{
    protected $widgetUtilisateurService;
    protected $userService;
    protected $widgetService;
    protected $sysModuleService;

    public function __construct(WidgetUtilisateurService $widgetUtilisateurService, UserService $userService, WidgetService $widgetService, SysModuleService $sysModuleService) {
        parent::__construct();
        $this->service  =  $widgetUtilisateurService;
        $this->widgetUtilisateurService = $widgetUtilisateurService;
        $this->userService = $userService;
        $this->widgetService = $widgetService;
        $this->sysModuleService = $sysModuleService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('widgetUtilisateur.index');
        
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.widgetUtilisateur.user_id') == null){
           $this->viewState->init('scope.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('scope.widgetUtilisateur.user_id') == null){
           $this->viewState->init('scope.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('admin') && $this->viewState->get('scope.widgetUtilisateur.user_id') == null){
           $this->viewState->init('scope.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $widgetUtilisateurs_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'widgetUtilisateurs_search',
                $this->viewState->get("filter.widgetUtilisateur.widgetUtilisateurs_search")
            )],
            $request->except(['widgetUtilisateurs_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->widgetUtilisateurService->prepareDataForIndexView($widgetUtilisateurs_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($widgetUtilisateur_partialViewName, $widgetUtilisateur_compact_value)->render();
        }

        return view('PkgWidgets::widgetUtilisateur.index', $widgetUtilisateur_compact_value);
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }
        if(Auth::user()->hasRole('admin')){
           $this->viewState->set('scope_form.widgetUtilisateur.user_id'  , $this->sessionState->get('user_id'));
        }


        $itemWidgetUtilisateur = $this->widgetUtilisateurService->createInstance();
        

        $users = $this->userService->all();
        $widgets = $this->widgetService->all();
        $sysModules = $this->sysModuleService->all();

        if (request()->ajax()) {
            return view('PkgWidgets::widgetUtilisateur._fields', compact('itemWidgetUtilisateur', 'users', 'widgets', 'sysModules'));
        }
        return view('PkgWidgets::widgetUtilisateur.create', compact('itemWidgetUtilisateur', 'users', 'widgets', 'sysModules'));
    }
    public function store(WidgetUtilisateurRequest $request) {
        $validatedData = $request->validated();
        $widgetUtilisateur = $this->widgetUtilisateurService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $widgetUtilisateur,
                'modelName' => __('PkgWidgets::widgetUtilisateur.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $widgetUtilisateur->id]
            );
        }

        return redirect()->route('widgetUtilisateurs.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $widgetUtilisateur,
                'modelName' => __('PkgWidgets::widgetUtilisateur.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('widgetUtilisateur.edit_' . $id);


        $itemWidgetUtilisateur = $this->widgetUtilisateurService->find($id);
        $this->authorize('view', $itemWidgetUtilisateur);


        $users = $this->userService->all();
        $widgets = $this->widgetService->all();
        $sysModules = $this->sysModuleService->all();


        if (request()->ajax()) {
            return view('PkgWidgets::widgetUtilisateur._fields', array_merge(compact('itemWidgetUtilisateur','users', 'widgets', 'sysModules'),));
        }

        return view('PkgWidgets::widgetUtilisateur.edit', array_merge(compact('itemWidgetUtilisateur','users', 'widgets', 'sysModules'),));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('widgetUtilisateur.edit_' . $id);


        $itemWidgetUtilisateur = $this->widgetUtilisateurService->find($id);
        $this->authorize('edit', $itemWidgetUtilisateur);


        $users = $this->userService->all();
        $widgets = $this->widgetService->all();
        $sysModules = $this->sysModuleService->all();


        if (request()->ajax()) {
            return view('PkgWidgets::widgetUtilisateur._fields', array_merge(compact('itemWidgetUtilisateur','users', 'widgets', 'sysModules'),));
        }

        return view('PkgWidgets::widgetUtilisateur.edit', array_merge(compact('itemWidgetUtilisateur','users', 'widgets', 'sysModules'),));

    }
    public function update(WidgetUtilisateurRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $widgetUtilisateur = $this->widgetUtilisateurService->find($id);
        $this->authorize('update', $widgetUtilisateur);

        $validatedData = $request->validated();
        $widgetUtilisateur = $this->widgetUtilisateurService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $widgetUtilisateur,
                'modelName' =>  __('PkgWidgets::widgetUtilisateur.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $widgetUtilisateur->id]
            );
        }

        return redirect()->route('widgetUtilisateurs.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $widgetUtilisateur,
                'modelName' =>  __('PkgWidgets::widgetUtilisateur.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $widgetUtilisateur = $this->widgetUtilisateurService->find($id);
        $this->authorize('delete', $widgetUtilisateur);

        $widgetUtilisateur = $this->widgetUtilisateurService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetUtilisateur,
                'modelName' =>  __('PkgWidgets::widgetUtilisateur.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('widgetUtilisateurs.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $widgetUtilisateur,
                'modelName' =>  __('PkgWidgets::widgetUtilisateur.singular')
                ])
        );

    }

    public function export($format)
    {
        $widgetUtilisateurs_data = $this->widgetUtilisateurService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new WidgetUtilisateurExport($widgetUtilisateurs_data,'csv'), 'widgetUtilisateur_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new WidgetUtilisateurExport($widgetUtilisateurs_data,'xlsx'), 'widgetUtilisateur_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new WidgetUtilisateurImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('widgetUtilisateurs.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('widgetUtilisateurs.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgWidgets::widgetUtilisateur.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getWidgetUtilisateurs()
    {
        $widgetUtilisateurs = $this->widgetUtilisateurService->all();
        return response()->json($widgetUtilisateurs);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $widgetUtilisateur = $this->widgetUtilisateurService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedWidgetUtilisateur = $this->widgetUtilisateurService->dataCalcul($widgetUtilisateur);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedWidgetUtilisateur
        ]);
    }
    

}