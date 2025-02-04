<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\FiliereService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgFormation\Services\ModuleService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgFormation\App\Requests\FiliereRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgFormation\App\Exports\FiliereExport;
use Modules\PkgFormation\App\Imports\FiliereImport;
use Modules\Core\Services\ContextState;

class BaseFiliereController extends AdminController
{
    protected $filiereService;

    public function __construct(FiliereService $filiereService) {
        parent::__construct();
        $this->filiereService = $filiereService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $filieres_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('filieres_search', '')],
            $request->except(['filieres_search', 'page', 'sort'])
        );

        // Paginer les filieres
        $filieres_data = $this->filiereService->paginate($filieres_params);

        // Récupérer les statistiques et les champs filtrables
        $filieres_stats = $this->filiereService->getfiliereStats();
        $filieres_filters = $this->filiereService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgFormation::filiere._table', compact('filieres_data', 'filieres_stats', 'filieres_filters'))->render();
        }

        return view('PkgFormation::filiere.index', compact('filieres_data', 'filieres_stats', 'filieres_filters'));
    }
    public function create() {
        $itemFiliere = $this->filiereService->createInstance();


        if (request()->ajax()) {
            return view('PkgFormation::filiere._fields', compact('itemFiliere'));
        }
        return view('PkgFormation::filiere.create', compact('itemFiliere'));
    }
    public function store(FiliereRequest $request) {
        $validatedData = $request->validated();
        $filiere = $this->filiereService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'entity_id' => $filiere->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $filiere,
                'modelName' => __('PkgFormation::filiere.singular')])
            ]);
        }

        return redirect()->route('filieres.edit',['filiere' => $filiere->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $filiere,
                'modelName' => __('PkgFormation::filiere.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('filiere_id', $id);
        
        $itemFiliere = $this->filiereService->find($id);
        $groupeService =  new GroupeService();
        $groupes_data =  $itemFiliere->groupes()->paginate(10);
        $groupes_stats = $groupeService->getgroupeStats();
        $groupes_filters = $groupeService->getFieldsFilterable();
        
        $moduleService =  new ModuleService();
        $modules_data =  $itemFiliere->modules()->paginate(10);
        $modules_stats = $moduleService->getmoduleStats();
        $modules_filters = $moduleService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgFormation::filiere._edit', compact('itemFiliere', 'groupes_data', 'modules_data', 'groupes_stats', 'modules_stats', 'groupes_filters', 'modules_filters'));
        }

        return view('PkgFormation::filiere.edit', compact('itemFiliere', 'groupes_data', 'modules_data', 'groupes_stats', 'modules_stats', 'groupes_filters', 'modules_filters'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('filiere_id', $id);
        
        $itemFiliere = $this->filiereService->find($id);
        $groupeService =  new GroupeService();
        $groupes_data =  $itemFiliere->groupes()->paginate(10);
        $groupes_stats = $groupeService->getgroupeStats();
        $groupes_filters = $groupeService->getFieldsFilterable();
        
        $moduleService =  new ModuleService();
        $modules_data =  $itemFiliere->modules()->paginate(10);
        $modules_stats = $moduleService->getmoduleStats();
        $modules_filters = $moduleService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgFormation::filiere._edit', compact('itemFiliere', 'groupes_data', 'modules_data', 'groupes_stats', 'modules_stats', 'groupes_filters', 'modules_filters'));
        }

        return view('PkgFormation::filiere.edit', compact('itemFiliere', 'groupes_data', 'modules_data', 'groupes_stats', 'modules_stats', 'groupes_filters', 'modules_filters'));

    }
    public function update(FiliereRequest $request, string $id) {

        $validatedData = $request->validated();
        $filiere = $this->filiereService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgFormation::filiere.singular')])
            ]);
        }

        return redirect()->route('filieres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgFormation::filiere.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $filiere = $this->filiereService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgFormation::filiere.singular')])
            ]);
        }

        return redirect()->route('filieres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgFormation::filiere.singular')
                ])
        );

    }

    public function export()
    {
        $filieres_data = $this->filiereService->all();
        return Excel::download(new FiliereExport($filieres_data), 'filiere_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new FiliereImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('filieres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('filieres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgFormation::filiere.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFilieres()
    {
        $filieres = $this->filiereService->all();
        return response()->json($filieres);
    }

}
