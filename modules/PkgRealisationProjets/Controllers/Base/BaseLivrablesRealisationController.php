<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\LivrablesRealisationService;
use Modules\PkgCreationProjet\Services\LivrableService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgRealisationProjets\App\Requests\LivrablesRealisationRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\LivrablesRealisationExport;
use Modules\PkgRealisationProjets\App\Imports\LivrablesRealisationImport;
use Modules\Core\Services\ContextState;

class BaseLivrablesRealisationController extends AdminController
{
    protected $livrablesRealisationService;
    protected $livrableService;

    public function __construct(LivrablesRealisationService $livrablesRealisationService, LivrableService $livrableService) {
        parent::__construct();
        $this->livrablesRealisationService = $livrablesRealisationService;
        $this->livrableService = $livrableService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $livrablesRealisations_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('livrablesRealisations_search', '')],
            $request->except(['livrablesRealisations_search', 'page', 'sort'])
        );

        // Paginer les livrablesRealisations
        $livrablesRealisations_data = $this->livrablesRealisationService->paginate($livrablesRealisations_params);

        // Récupérer les statistiques et les champs filtrables
        $livrablesRealisations_stats = $this->livrablesRealisationService->getlivrablesRealisationStats();
        $livrablesRealisations_filters = $this->livrablesRealisationService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgRealisationProjets::livrablesRealisation._table', compact('livrablesRealisations_data', 'livrablesRealisations_stats', 'livrablesRealisations_filters'))->render();
        }

        return view('PkgRealisationProjets::livrablesRealisation.index', compact('livrablesRealisations_data', 'livrablesRealisations_stats', 'livrablesRealisations_filters'));
    }
    public function create() {
        $itemLivrablesRealisation = $this->livrablesRealisationService->createInstance();
        $livrables = $this->livrableService->all();


        if (request()->ajax()) {
            return view('PkgRealisationProjets::livrablesRealisation._fields', compact('itemLivrablesRealisation', 'livrables'));
        }
        return view('PkgRealisationProjets::livrablesRealisation.create', compact('itemLivrablesRealisation', 'livrables'));
    }
    public function store(LivrablesRealisationRequest $request) {
        $validatedData = $request->validated();
        $livrablesRealisation = $this->livrablesRealisationService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' => __('PkgRealisationProjets::livrablesRealisation.singular')])
            ]);
        }

        return redirect()->route('livrablesRealisations.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' => __('PkgRealisationProjets::livrablesRealisation.singular')
            ])
        );
    }
    public function show(string $id) {
        $itemLivrablesRealisation = $this->livrablesRealisationService->find($id);
        $livrables = $this->livrableService->all();


        if (request()->ajax()) {
            return view('PkgRealisationProjets::livrablesRealisation._fields', compact('itemLivrablesRealisation', 'livrables'));
        }

        return view('PkgRealisationProjets::livrablesRealisation.show', compact('itemLivrablesRealisation'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('livrables_realisation_id', $id);
        
        $itemLivrablesRealisation = $this->livrablesRealisationService->find($id);
        $livrables = $this->livrableService->all();

        if (request()->ajax()) {
            return view('PkgRealisationProjets::livrablesRealisation._fields', compact('itemLivrablesRealisation', 'livrables'));
        }

        return view('PkgRealisationProjets::livrablesRealisation.edit', compact('itemLivrablesRealisation', 'livrables'));

    }
    public function update(LivrablesRealisationRequest $request, string $id) {

        $validatedData = $request->validated();
        $livrablesRealisation = $this->livrablesRealisationService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' =>  __('PkgRealisationProjets::livrablesRealisation.singular')])
            ]);
        }

        return redirect()->route('livrablesRealisations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' =>  __('PkgRealisationProjets::livrablesRealisation.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $livrablesRealisation = $this->livrablesRealisationService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' =>  __('PkgRealisationProjets::livrablesRealisation.singular')])
            ]);
        }

        return redirect()->route('livrablesRealisations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' =>  __('PkgRealisationProjets::livrablesRealisation.singular')
                ])
        );

    }

    public function export()
    {
        $livrablesRealisations_data = $this->livrablesRealisationService->all();
        return Excel::download(new LivrablesRealisationExport($livrablesRealisations_data), 'livrablesRealisation_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new LivrablesRealisationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('livrablesRealisations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('livrablesRealisations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationProjets::livrablesRealisation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getLivrablesRealisations()
    {
        $livrablesRealisations = $this->livrablesRealisationService->all();
        return response()->json($livrablesRealisations);
    }

}
