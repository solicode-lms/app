<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\FiliereService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgFormation\Services\ModuleService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgFormation\App\Requests\FiliereRequest;
use Modules\PkgFormation\Models\Filiere;
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
        
        $this->viewState->setContextKeyIfEmpty('filiere.index');


        // Extraire les paramètres de recherche, page, et filtres
        $filieres_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('filieres_search', $this->viewState->get("filter.filiere.filieres_search"))],
            $request->except(['filieres_search', 'page', 'sort'])
        );

        // Paginer les filieres
        $filieres_data = $this->filiereService->paginate($filieres_params);

        // Récupérer les statistiques et les champs filtrables
        $filieres_stats = $this->filiereService->getfiliereStats();
        $filieres_filters = $this->filiereService->getFieldsFilterable();
        $filiere_instance =  $this->filiereService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgFormation::filiere._table', compact('filieres_data', 'filieres_stats', 'filieres_filters','filiere_instance'))->render();
        }

        return view('PkgFormation::filiere.index', compact('filieres_data', 'filieres_stats', 'filieres_filters','filiere_instance'));
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
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $filiere,
                'modelName' => __('PkgFormation::filiere.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $filiere->id]
            );
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

        $this->viewState->setContextKey('filiere.edit_' . $id);

        $itemFiliere = $this->filiereService->find($id);



        $this->viewState->set('scope.groupe.filiere_id', $id);
        $groupeService =  new GroupeService();
        $groupes_data =  $itemFiliere->groupes()->paginate(10);
        $groupes_stats = $groupeService->getgroupeStats();
        $groupes_filters = $groupeService->getFieldsFilterable();
        $groupe_instance =  $groupeService->createInstance();

        $this->viewState->set('scope.module.filiere_id', $id);
        $moduleService =  new ModuleService();
        $modules_data =  $itemFiliere->modules()->paginate(10);
        $modules_stats = $moduleService->getmoduleStats();
        $modules_filters = $moduleService->getFieldsFilterable();
        $module_instance =  $moduleService->createInstance();

        $this->viewState->set('scope.projet.filiere_id', $id);
        $projetService =  new ProjetService();
        $projets_data =  $itemFiliere->projets()->paginate(10);
        $projets_stats = $projetService->getprojetStats();
        $projets_filters = $projetService->getFieldsFilterable();
        $projet_instance =  $projetService->createInstance();

        if (request()->ajax()) {
            return view('PkgFormation::filiere._edit', compact('itemFiliere', 'groupes_data', 'modules_data', 'projets_data', 'groupes_stats', 'modules_stats', 'projets_stats', 'groupes_filters', 'modules_filters', 'projets_filters', 'groupe_instance', 'module_instance', 'projet_instance'));
        }

        return view('PkgFormation::filiere.edit', compact('itemFiliere', 'groupes_data', 'modules_data', 'projets_data', 'groupes_stats', 'modules_stats', 'projets_stats', 'groupes_filters', 'modules_filters', 'projets_filters', 'groupe_instance', 'module_instance', 'projet_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('filiere.edit_' . $id);

        $itemFiliere = $this->filiereService->find($id);



        $this->viewState->set('scope.groupe.filiere_id', $id);
        $groupeService =  new GroupeService();
        $groupes_data =  $itemFiliere->groupes()->paginate(10);
        $groupes_stats = $groupeService->getgroupeStats();
        $groupes_filters = $groupeService->getFieldsFilterable();
        $groupe_instance =  $groupeService->createInstance();

        $this->viewState->set('scope.module.filiere_id', $id);
        $moduleService =  new ModuleService();
        $modules_data =  $itemFiliere->modules()->paginate(10);
        $modules_stats = $moduleService->getmoduleStats();
        $modules_filters = $moduleService->getFieldsFilterable();
        $module_instance =  $moduleService->createInstance();

        $this->viewState->set('scope.projet.filiere_id', $id);
        $projetService =  new ProjetService();
        $projets_data =  $itemFiliere->projets()->paginate(10);
        $projets_stats = $projetService->getprojetStats();
        $projets_filters = $projetService->getFieldsFilterable();
        $projet_instance =  $projetService->createInstance();

        if (request()->ajax()) {
            return view('PkgFormation::filiere._edit', compact('itemFiliere', 'groupes_data', 'modules_data', 'projets_data', 'groupes_stats', 'modules_stats', 'projets_stats', 'groupes_filters', 'modules_filters', 'projets_filters', 'groupe_instance', 'module_instance', 'projet_instance'));
        }

        return view('PkgFormation::filiere.edit', compact('itemFiliere', 'groupes_data', 'modules_data', 'projets_data', 'groupes_stats', 'modules_stats', 'projets_stats', 'groupes_filters', 'modules_filters', 'projets_filters', 'groupe_instance', 'module_instance', 'projet_instance'));

    }
    public function update(FiliereRequest $request, string $id) {

        $validatedData = $request->validated();
        $filiere = $this->filiereService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgFormation::filiere.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $filiere->id]
            );
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
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgFormation::filiere.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('filieres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgFormation::filiere.singular')
                ])
        );

    }

    public function export($format)
    {
        $filieres_data = $this->filiereService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new FiliereExport($filieres_data,'csv'), 'filiere_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new FiliereExport($filieres_data,'xlsx'), 'filiere_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $filiere = $this->filiereService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedFiliere = $this->filiereService->dataCalcul($filiere);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedFiliere
        ]);
    }
    

}
