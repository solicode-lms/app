<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\LivrablesRealisationService;
use Modules\PkgCreationProjet\Services\LivrableService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationProjets\App\Requests\LivrablesRealisationRequest;
use Modules\PkgRealisationProjets\Models\LivrablesRealisation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\LivrablesRealisationExport;
use Modules\PkgRealisationProjets\App\Imports\LivrablesRealisationImport;
use Modules\Core\Services\ContextState;

class BaseLivrablesRealisationController extends AdminController
{
    protected $livrablesRealisationService;
    protected $livrableService;
    protected $realisationProjetService;

    public function __construct(LivrablesRealisationService $livrablesRealisationService, LivrableService $livrableService, RealisationProjetService $realisationProjetService) {
        parent::__construct();
        $this->livrablesRealisationService = $livrablesRealisationService;
        $this->livrableService = $livrableService;
        $this->realisationProjetService = $realisationProjetService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('livrablesRealisation.index');
        // ownedByUser
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->init('filter.livrablesRealisation.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }


        // Extraire les paramètres de recherche, page, et filtres
        $livrablesRealisations_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('livrablesRealisations_search', $this->viewState->get("filter.livrablesRealisation.livrablesRealisations_search"))],
            $request->except(['livrablesRealisations_search', 'page', 'sort'])
        );

        // Paginer les livrablesRealisations
        $livrablesRealisations_data = $this->livrablesRealisationService->paginate($livrablesRealisations_params);

        // Récupérer les statistiques et les champs filtrables
        $livrablesRealisations_stats = $this->livrablesRealisationService->getlivrablesRealisationStats();
        $livrablesRealisations_filters = $this->livrablesRealisationService->getFieldsFilterable();
        $livrablesRealisation_instance =  $this->livrablesRealisationService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgRealisationProjets::livrablesRealisation._table', compact('livrablesRealisations_data', 'livrablesRealisations_stats', 'livrablesRealisations_filters','livrablesRealisation_instance'))->render();
        }

        return view('PkgRealisationProjets::livrablesRealisation.index', compact('livrablesRealisations_data', 'livrablesRealisations_stats', 'livrablesRealisations_filters','livrablesRealisation_instance'));
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.livrablesRealisation.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }


        $itemLivrablesRealisation = $this->livrablesRealisationService->createInstance();
        

        $livrables = $this->livrableService->all();
        $realisationProjets = $this->realisationProjetService->all();

        if (request()->ajax()) {
            return view('PkgRealisationProjets::livrablesRealisation._fields', compact('itemLivrablesRealisation', 'livrables', 'realisationProjets'));
        }
        return view('PkgRealisationProjets::livrablesRealisation.create', compact('itemLivrablesRealisation', 'livrables', 'realisationProjets'));
    }
    public function store(LivrablesRealisationRequest $request) {
        $validatedData = $request->validated();
        $livrablesRealisation = $this->livrablesRealisationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' => __('PkgRealisationProjets::livrablesRealisation.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $livrablesRealisation->id]
            );
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

        $this->viewState->setContextKey('livrablesRealisation.edit_' . $id);


        $itemLivrablesRealisation = $this->livrablesRealisationService->find($id);
        $this->authorize('view', $itemLivrablesRealisation);


        $livrables = $this->livrableService->all();
        $realisationProjets = $this->realisationProjetService->all();


        if (request()->ajax()) {
            return view('PkgRealisationProjets::livrablesRealisation._fields', compact('itemLivrablesRealisation', 'livrables', 'realisationProjets'));
        }

        return view('PkgRealisationProjets::livrablesRealisation.edit', compact('itemLivrablesRealisation', 'livrables', 'realisationProjets'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('livrablesRealisation.edit_' . $id);


        $itemLivrablesRealisation = $this->livrablesRealisationService->find($id);
        $this->authorize('edit', $itemLivrablesRealisation);


        $livrables = $this->livrableService->all();
        $realisationProjets = $this->realisationProjetService->all();


        if (request()->ajax()) {
            return view('PkgRealisationProjets::livrablesRealisation._fields', compact('itemLivrablesRealisation', 'livrables', 'realisationProjets'));
        }

        return view('PkgRealisationProjets::livrablesRealisation.edit', compact('itemLivrablesRealisation', 'livrables', 'realisationProjets'));

    }
    public function update(LivrablesRealisationRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $livrablesRealisation = $this->livrablesRealisationService->find($id);
        $this->authorize('update', $livrablesRealisation);

        $validatedData = $request->validated();
        $livrablesRealisation = $this->livrablesRealisationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' =>  __('PkgRealisationProjets::livrablesRealisation.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $livrablesRealisation->id]
            );
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
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $livrablesRealisation = $this->livrablesRealisationService->find($id);
        $this->authorize('delete', $livrablesRealisation);

        $livrablesRealisation = $this->livrablesRealisationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' =>  __('PkgRealisationProjets::livrablesRealisation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('livrablesRealisations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $livrablesRealisation,
                'modelName' =>  __('PkgRealisationProjets::livrablesRealisation.singular')
                ])
        );

    }

    public function export($format)
    {
        $livrablesRealisations_data = $this->livrablesRealisationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new LivrablesRealisationExport($livrablesRealisations_data,'csv'), 'livrablesRealisation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new LivrablesRealisationExport($livrablesRealisations_data,'xlsx'), 'livrablesRealisation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $livrablesRealisation = $this->livrablesRealisationService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedLivrablesRealisation = $this->livrablesRealisationService->dataCalcul($livrablesRealisation);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedLivrablesRealisation
        ]);
    }
    

}
