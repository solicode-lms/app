<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\LivrableService;
use Modules\PkgCreationProjet\Services\NatureLivrableService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCreationProjet\App\Requests\LivrableRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\LivrableExport;
use Modules\PkgCreationProjet\App\Imports\LivrableImport;
use Modules\Core\Services\ContextState;

class BaseLivrableController extends AdminController
{
    protected $livrableService;
    protected $natureLivrableService;
    protected $projetService;

    public function __construct(LivrableService $livrableService, NatureLivrableService $natureLivrableService, ProjetService $projetService) {
        parent::__construct();
        $this->livrableService = $livrableService;
        $this->natureLivrableService = $natureLivrableService;
        $this->projetService = $projetService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $livrables_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('livrables_search', '')],
            $request->except(['livrables_search', 'page', 'sort'])
        );

        // Paginer les livrables
        $livrables_data = $this->livrableService->paginate($livrables_params);

        // Récupérer les statistiques et les champs filtrables
        $livrables_stats = $this->livrableService->getlivrableStats();
        $livrables_filters = $this->livrableService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgCreationProjet::livrable._table', compact('livrables_data', 'livrables_stats', 'livrables_filters'))->render();
        }

        return view('PkgCreationProjet::livrable.index', compact('livrables_data', 'livrables_stats', 'livrables_filters'));
    }
    public function create() {
        $itemLivrable = $this->livrableService->createInstance();
        $natureLivrables = $this->natureLivrableService->all();
        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::livrable._fields', compact('itemLivrable', 'natureLivrables', 'projets'));
        }
        return view('PkgCreationProjet::livrable.create', compact('itemLivrable', 'natureLivrables', 'projets'));
    }
    public function store(LivrableRequest $request) {
        $validatedData = $request->validated();
        $livrable = $this->livrableService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'livrable_id' => $livrable->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $livrable,
                'modelName' => __('PkgCreationProjet::livrable.singular')])
            ]);
        }

        return redirect()->route('livrables.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $livrable,
                'modelName' => __('PkgCreationProjet::livrable.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('livrable_id', $id);
        
        $itemLivrable = $this->livrableService->find($id);
        $natureLivrables = $this->natureLivrableService->all();
        $projets = $this->projetService->all();

        if (request()->ajax()) {
            return view('PkgCreationProjet::livrable._edit', compact('itemLivrable', 'natureLivrables', 'projets'));
        }

        return view('PkgCreationProjet::livrable.edit', compact('itemLivrable', 'natureLivrables', 'projets'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('livrable_id', $id);
        
        $itemLivrable = $this->livrableService->find($id);
        $natureLivrables = $this->natureLivrableService->all();
        $projets = $this->projetService->all();

        if (request()->ajax()) {
            return view('PkgCreationProjet::livrable._edit', compact('itemLivrable', 'natureLivrables', 'projets'));
        }

        return view('PkgCreationProjet::livrable.edit', compact('itemLivrable', 'natureLivrables', 'projets'));

    }
    public function update(LivrableRequest $request, string $id) {

        $validatedData = $request->validated();
        $livrable = $this->livrableService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')])
            ]);
        }

        return redirect()->route('livrables.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $livrable = $this->livrableService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')])
            ]);
        }

        return redirect()->route('livrables.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')
                ])
        );

    }

    public function export()
    {
        $livrables_data = $this->livrableService->all();
        return Excel::download(new LivrableExport($livrables_data), 'livrable_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new LivrableImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('livrables.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('livrables.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::livrable.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getLivrables()
    {
        $livrables = $this->livrableService->all();
        return response()->json($livrables);
    }

}
