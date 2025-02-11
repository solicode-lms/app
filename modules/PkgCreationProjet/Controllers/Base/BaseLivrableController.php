<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\LivrableService;
use Modules\PkgCreationProjet\Services\NatureLivrableService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationProjet\App\Requests\LivrableRequest;
use Modules\PkgCreationProjet\Models\Livrable;
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
        
        $this->viewState->setContextKeyIfEmpty('livrable.index');
        // Extraire les paramètres de recherche, page, et filtres
        $livrables_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('livrables_search', $this->viewState->get("filter.livrable.livrables_search"))],
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
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $livrable,
                'modelName' => __('PkgCreationProjet::livrable.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $livrable->id]
            );
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
            return view('PkgCreationProjet::livrable._fields', compact('itemLivrable', 'natureLivrables', 'projets'));
        }

        return view('PkgCreationProjet::livrable.edit', compact('itemLivrable', 'natureLivrables', 'projets'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('livrable.edit_' . $id);
        
        $itemLivrable = $this->livrableService->find($id);
        $natureLivrables = $this->natureLivrableService->all();
        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::livrable._fields', compact('itemLivrable', 'natureLivrables', 'projets'));
        }

        return view('PkgCreationProjet::livrable.edit', compact('itemLivrable', 'natureLivrables', 'projets'));

    }
    public function update(LivrableRequest $request, string $id) {

        $validatedData = $request->validated();
        $livrable = $this->livrableService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $livrable->id]
            );
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
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('livrables.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')
                ])
        );

    }

    public function export($format)
    {
        $livrables_data = $this->livrableService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new LivrableExport($livrables_data,'csv'), 'livrable_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new LivrableExport($livrables_data,'xlsx'), 'livrable_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $livrable = $this->livrableService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedLivrable = $this->livrableService->dataCalcul($livrable);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedLivrable
        ]);
    }
    


}
