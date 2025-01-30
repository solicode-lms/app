<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgApprenants\Services\NationaliteService;
use Modules\PkgApprenants\Services\NiveauxScolaireService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgApprenants\App\Requests\ApprenantRequest;
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

    public function __construct(ApprenantService $apprenantService, GroupeService $groupeService, NationaliteService $nationaliteService, NiveauxScolaireService $niveauxScolaireService) {
        parent::__construct();
        $this->apprenantService = $apprenantService;
        $this->groupeService = $groupeService;
        $this->nationaliteService = $nationaliteService;
        $this->niveauxScolaireService = $niveauxScolaireService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $apprenants_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('apprenants_search', '')],
            $request->except(['apprenants_search', 'page', 'sort'])
        );

        // Paginer les apprenants
        $apprenants_data = $this->apprenantService->paginate($apprenants_params);

        // Récupérer les statistiques et les champs filtrables
        $apprenants_stats = $this->apprenantService->getapprenantStats();
        $apprenants_filters = $this->apprenantService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgApprenants::apprenant._table', compact('apprenants_data', 'apprenants_stats', 'apprenants_filters'))->render();
        }

        return view('PkgApprenants::apprenant.index', compact('apprenants_data', 'apprenants_stats', 'apprenants_filters'));
    }
    public function create() {
        $itemApprenant = $this->apprenantService->createInstance();
        $groupes = $this->groupeService->all();
        $nationalites = $this->nationaliteService->all();
        $niveauxScolaires = $this->niveauxScolaireService->all();


        if (request()->ajax()) {
            return view('PkgApprenants::apprenant._fields', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires'));
        }
        return view('PkgApprenants::apprenant.create', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires'));
    }
    public function store(ApprenantRequest $request) {
        $validatedData = $request->validated();
        $apprenant = $this->apprenantService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $apprenant,
                'modelName' => __('PkgApprenants::apprenant.singular')])
            ]);
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
        $itemApprenant = $this->apprenantService->find($id);
        $groupes = $this->groupeService->all();
        $nationalites = $this->nationaliteService->all();
        $niveauxScolaires = $this->niveauxScolaireService->all();


        if (request()->ajax()) {
            return view('PkgApprenants::apprenant._fields', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires'));
        }

        return view('PkgApprenants::apprenant.show', compact('itemApprenant'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('apprenant_id', $id);
        
        $itemApprenant = $this->apprenantService->find($id);
        $groupes = $this->groupeService->all();
        $nationalites = $this->nationaliteService->all();
        $niveauxScolaires = $this->niveauxScolaireService->all();

        if (request()->ajax()) {
            return view('PkgApprenants::apprenant._fields', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires'));
        }

        return view('PkgApprenants::apprenant.edit', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires'));

    }
    public function update(ApprenantRequest $request, string $id) {

        $validatedData = $request->validated();
        $apprenant = $this->apprenantService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $apprenant,
                'modelName' =>  __('PkgApprenants::apprenant.singular')])
            ]);
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
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenant,
                'modelName' =>  __('PkgApprenants::apprenant.singular')])
            ]);
        }

        return redirect()->route('apprenants.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenant,
                'modelName' =>  __('PkgApprenants::apprenant.singular')
                ])
        );

    }

    public function export()
    {
        $apprenants_data = $this->apprenantService->all();
        return Excel::download(new ApprenantExport($apprenants_data), 'apprenant_export.xlsx');
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

}
