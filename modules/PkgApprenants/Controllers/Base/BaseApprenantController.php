<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgApprenants\Services\NationaliteService;
use Modules\PkgApprenants\Services\NiveauxScolaireService;
use Modules\PkgAutorisation\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprenants\App\Requests\ApprenantRequest;
use Modules\PkgApprenants\Models\Apprenant;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprenants\App\Exports\ApprenantExport;
use Modules\PkgApprenants\App\Imports\ApprenantImport;
use Modules\Core\Services\ContextState;

class BaseApprenantController extends AdminController
{
    protected $apprenantService;
    protected $groupeService;
    protected $nationaliteService;
    protected $niveauxScolaireService;
    protected $userService;

    public function __construct(ApprenantService $apprenantService, GroupeService $groupeService, NationaliteService $nationaliteService, NiveauxScolaireService $niveauxScolaireService, UserService $userService) {
        parent::__construct();
        $this->service  =  $apprenantService;
        $this->apprenantService = $apprenantService;
        $this->groupeService = $groupeService;
        $this->nationaliteService = $nationaliteService;
        $this->niveauxScolaireService = $niveauxScolaireService;
        $this->userService = $userService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('apprenant.index');
        
        $viewType = $this->viewState->get('view_type', 'table');
        $viewTypes = $this->getService()->getViewTypes();
        

        // scopeDataByRole pour Model
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.apprenant.groupes.formateurs.id'  , $this->sessionState->get('formateur_id'));
        }


        // Extraire les paramètres de recherche, page, et filtres
        $apprenants_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('apprenants_search', $this->viewState->get("filter.apprenant.apprenants_search"))],
            $request->except(['apprenants_search', 'page', 'sort'])
        );

        // Paginer les apprenants
        $apprenants_data = $this->apprenantService->paginate($apprenants_params);

        // Récupérer les statistiques et les champs filtrables
        $apprenants_stats = $this->apprenantService->getapprenantStats();
        $this->viewState->set('stats.apprenant.stats'  , $apprenants_stats);
        $apprenants_filters = $this->apprenantService->getFieldsFilterable();
        $apprenant_instance =  $this->apprenantService->createInstance();
        
        $partialViewName =  $partialViewName = $this->getService()->getPartialViewName($viewType);

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($partialViewName, compact('viewTypes','apprenants_data', 'apprenants_stats', 'apprenants_filters','apprenant_instance'))->render();
        }

        return view('PkgApprenants::apprenant.index', compact('viewTypes','viewType','apprenants_data', 'apprenants_stats', 'apprenants_filters','apprenant_instance'));
    }
    public function create() {


        $itemApprenant = $this->apprenantService->createInstance();
        

        $nationalites = $this->nationaliteService->all();
        $niveauxScolaires = $this->niveauxScolaireService->all();
        $users = $this->userService->all();
        $groupes = $this->groupeService->all();

        if (request()->ajax()) {
            return view('PkgApprenants::apprenant._fields', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires', 'users'));
        }
        return view('PkgApprenants::apprenant.create', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires', 'users'));
    }
    public function store(ApprenantRequest $request) {
        $validatedData = $request->validated();
        $apprenant = $this->apprenantService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $apprenant,
                'modelName' => __('PkgApprenants::apprenant.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $apprenant->id]
            );
        }

        return redirect()->route('apprenants.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $apprenant,
                'modelName' => __('PkgApprenants::apprenant.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('apprenant.edit_' . $id);


        $itemApprenant = $this->apprenantService->find($id);


        $nationalites = $this->nationaliteService->all();
        $niveauxScolaires = $this->niveauxScolaireService->all();
        $users = $this->userService->all();
        $groupes = $this->groupeService->all();
        

        if (request()->ajax()) {
            return view('PkgApprenants::apprenant._fields', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires', 'users'));
        }

        return view('PkgApprenants::apprenant.edit', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires', 'users'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('apprenant.edit_' . $id);


        $itemApprenant = $this->apprenantService->find($id);


        $nationalites = $this->nationaliteService->all();
        $niveauxScolaires = $this->niveauxScolaireService->all();
        $users = $this->userService->all();
        $groupes = $this->groupeService->all();


        if (request()->ajax()) {
            return view('PkgApprenants::apprenant._fields', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires', 'users'));
        }

        return view('PkgApprenants::apprenant.edit', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires', 'users'));

    }
    public function update(ApprenantRequest $request, string $id) {

        $validatedData = $request->validated();
        $apprenant = $this->apprenantService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $apprenant,
                'modelName' =>  __('PkgApprenants::apprenant.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $apprenant->id]
            );
        }

        return redirect()->route('apprenants.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $apprenant,
                'modelName' =>  __('PkgApprenants::apprenant.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $apprenant = $this->apprenantService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenant,
                'modelName' =>  __('PkgApprenants::apprenant.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('apprenants.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenant,
                'modelName' =>  __('PkgApprenants::apprenant.singular')
                ])
        );

    }

    public function export($format)
    {
        $apprenants_data = $this->apprenantService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new ApprenantExport($apprenants_data,'csv'), 'apprenant_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new ApprenantExport($apprenants_data,'xlsx'), 'apprenant_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new ApprenantImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('apprenants.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('apprenants.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprenants::apprenant.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getApprenants()
    {
        $apprenants = $this->apprenantService->all();
        return response()->json($apprenants);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $apprenant = $this->apprenantService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedApprenant = $this->apprenantService->dataCalcul($apprenant);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedApprenant
        ]);
    }
    
    public function initPassword(Request $request, string $id) {
        $apprenant = $this->apprenantService->initPassword($id);
        if ($request->ajax()) {
            $message = "Le mot de passe a été modifier avec succès";
            return JsonResponseHelper::success(
                $message
            );
        }
        return redirect()->route('Apprenant.index')->with(
            'success',
            "Le mot de passe a été modifier avec succès"
        );
    }
    
}