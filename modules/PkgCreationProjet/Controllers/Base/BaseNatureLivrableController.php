<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\NatureLivrableService;
use Modules\PkgCreationProjet\Services\LivrableService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCreationProjet\App\Requests\NatureLivrableRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\NatureLivrableExport;
use Modules\PkgCreationProjet\App\Imports\NatureLivrableImport;
use Modules\Core\Services\ContextState;

class BaseNatureLivrableController extends AdminController
{
    protected $natureLivrableService;

    public function __construct(NatureLivrableService $natureLivrableService) {
        parent::__construct();
        $this->natureLivrableService = $natureLivrableService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $natureLivrables_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('natureLivrables_search', '')],
            $request->except(['natureLivrables_search', 'page', 'sort'])
        );

        // Paginer les natureLivrables
        $natureLivrables_data = $this->natureLivrableService->paginate($natureLivrables_params);

        // Récupérer les statistiques et les champs filtrables
        $natureLivrables_stats = $this->natureLivrableService->getnatureLivrableStats();
        $natureLivrables_filters = $this->natureLivrableService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgCreationProjet::natureLivrable._table', compact('natureLivrables_data', 'natureLivrables_stats', 'natureLivrables_filters'))->render();
        }

        return view('PkgCreationProjet::natureLivrable.index', compact('natureLivrables_data', 'natureLivrables_stats', 'natureLivrables_filters'));
    }
    public function create() {
        $itemNatureLivrable = $this->natureLivrableService->createInstance();


        if (request()->ajax()) {
            return view('PkgCreationProjet::natureLivrable._fields', compact('itemNatureLivrable'));
        }
        return view('PkgCreationProjet::natureLivrable.create', compact('itemNatureLivrable'));
    }
    public function store(NatureLivrableRequest $request) {
        $validatedData = $request->validated();
        $natureLivrable = $this->natureLivrableService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'nature_livrable_id' => $natureLivrable->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' => __('PkgCreationProjet::natureLivrable.singular')])
            ]);
        }

        return redirect()->route('natureLivrables.edit',['natureLivrable' => $natureLivrable->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' => __('PkgCreationProjet::natureLivrable.singular')
            ])
        );
    }
    public function show(string $id) {
        $itemNatureLivrable = $this->natureLivrableService->find($id);


        if (request()->ajax()) {
            return view('PkgCreationProjet::natureLivrable._fields', compact('itemNatureLivrable'));
        }

        return view('PkgCreationProjet::natureLivrable.show', compact('itemNatureLivrable'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('nature_livrable_id', $id);
        
        $itemNatureLivrable = $this->natureLivrableService->find($id);
        $livrableService =  new LivrableService();
        $livrables_data =  $itemNatureLivrable->livrables()->paginate(10);
        $livrables_stats = $livrableService->getlivrableStats();
        $livrables_filters = $livrableService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgCreationProjet::natureLivrable._fields', compact('itemNatureLivrable', 'livrables_data', 'livrables_stats', 'livrables_filters'));
        }

        return view('PkgCreationProjet::natureLivrable.edit', compact('itemNatureLivrable', 'livrables_data', 'livrables_stats', 'livrables_filters'));

    }
    public function update(NatureLivrableRequest $request, string $id) {

        $validatedData = $request->validated();
        $natureLivrable = $this->natureLivrableService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' =>  __('PkgCreationProjet::natureLivrable.singular')])
            ]);
        }

        return redirect()->route('natureLivrables.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' =>  __('PkgCreationProjet::natureLivrable.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $natureLivrable = $this->natureLivrableService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' =>  __('PkgCreationProjet::natureLivrable.singular')])
            ]);
        }

        return redirect()->route('natureLivrables.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' =>  __('PkgCreationProjet::natureLivrable.singular')
                ])
        );

    }

    public function export()
    {
        $natureLivrables_data = $this->natureLivrableService->all();
        return Excel::download(new NatureLivrableExport($natureLivrables_data), 'natureLivrable_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new NatureLivrableImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('natureLivrables.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('natureLivrables.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::natureLivrable.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNatureLivrables()
    {
        $natureLivrables = $this->natureLivrableService->all();
        return response()->json($natureLivrables);
    }

}
