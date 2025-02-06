<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService;
use Modules\PkgRealisationProjets\Services\ValidationService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgRealisationProjets\App\Requests\RealisationProjetRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\RealisationProjetExport;
use Modules\PkgRealisationProjets\App\Imports\RealisationProjetImport;
use Modules\Core\Services\ContextState;

class BaseRealisationProjetController extends AdminController
{
    protected $realisationProjetService;
    protected $affectationProjetService;
    protected $apprenantService;
    protected $etatsRealisationProjetService;

    public function __construct(RealisationProjetService $realisationProjetService, AffectationProjetService $affectationProjetService, ApprenantService $apprenantService, EtatsRealisationProjetService $etatsRealisationProjetService) {
        parent::__construct();
        $this->realisationProjetService = $realisationProjetService;
        $this->affectationProjetService = $affectationProjetService;
        $this->apprenantService = $apprenantService;
        $this->etatsRealisationProjetService = $etatsRealisationProjetService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $realisationProjets_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('realisationProjets_search', '')],
            $request->except(['realisationProjets_search', 'page', 'sort'])
        );

        // Paginer les realisationProjets
        $realisationProjets_data = $this->realisationProjetService->paginate($realisationProjets_params);

        // Récupérer les statistiques et les champs filtrables
        $realisationProjets_stats = $this->realisationProjetService->getrealisationProjetStats();
        $realisationProjets_filters = $this->realisationProjetService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgRealisationProjets::realisationProjet._table', compact('realisationProjets_data', 'realisationProjets_stats', 'realisationProjets_filters'))->render();
        }

        return view('PkgRealisationProjets::realisationProjet.index', compact('realisationProjets_data', 'realisationProjets_stats', 'realisationProjets_filters'));
    }
    public function create() {
        $itemRealisationProjet = $this->realisationProjetService->createInstance();
        $affectationProjets = $this->affectationProjetService->all();
        $apprenants = $this->apprenantService->all();
        $etatsRealisationProjets = $this->etatsRealisationProjetService->all();


        if (request()->ajax()) {
            return view('PkgRealisationProjets::realisationProjet._fields', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets'));
        }
        return view('PkgRealisationProjets::realisationProjet.create', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets'));
    }
    public function store(RealisationProjetRequest $request) {
        $validatedData = $request->validated();
        $realisationProjet = $this->realisationProjetService->create($validatedData);

        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'entity_id' => $realisationProjet->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' => __('PkgRealisationProjets::realisationProjet.singular')])
            ]);
        }

        return redirect()->route('realisationProjets.edit',['realisationProjet' => $realisationProjet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' => __('PkgRealisationProjets::realisationProjet.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('realisation_projet_id', $id);
        
        $itemRealisationProjet = $this->realisationProjetService->find($id);
        $affectationProjets = $this->affectationProjetService->all();
        $apprenants = $this->apprenantService->all();
        $etatsRealisationProjets = $this->etatsRealisationProjetService->all();
        $validationService =  new ValidationService();
        $validations_data =  $itemRealisationProjet->validations()->paginate(10);
        $validations_stats = $validationService->getvalidationStats();
        $validations_filters = $validationService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgRealisationProjets::realisationProjet._edit', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets', 'validations_data', 'validations_stats', 'validations_filters'));
        }

        return view('PkgRealisationProjets::realisationProjet.edit', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets', 'validations_data', 'validations_stats', 'validations_filters'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('realisation_projet_id', $id);
        
        $itemRealisationProjet = $this->realisationProjetService->find($id);
        $affectationProjets = $this->affectationProjetService->all();
        $apprenants = $this->apprenantService->all();
        $etatsRealisationProjets = $this->etatsRealisationProjetService->all();
        $validationService =  new ValidationService();
        $validations_data =  $itemRealisationProjet->validations()->paginate(10);
        $validations_stats = $validationService->getvalidationStats();
        $validations_filters = $validationService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgRealisationProjets::realisationProjet._edit', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets', 'validations_data', 'validations_stats', 'validations_filters'));
        }

        return view('PkgRealisationProjets::realisationProjet.edit', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets', 'validations_data', 'validations_stats', 'validations_filters'));

    }
    public function update(RealisationProjetRequest $request, string $id) {

        $validatedData = $request->validated();
        $realisationProjet = $this->realisationProjetService->update($id, $validatedData);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' =>  __('PkgRealisationProjets::realisationProjet.singular')])
            ]);
        }

        return redirect()->route('realisationProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' =>  __('PkgRealisationProjets::realisationProjet.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $realisationProjet = $this->realisationProjetService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' =>  __('PkgRealisationProjets::realisationProjet.singular')])
            ]);
        }

        return redirect()->route('realisationProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' =>  __('PkgRealisationProjets::realisationProjet.singular')
                ])
        );

    }

    public function export($format)
    {
        $realisationProjets_data = $this->realisationProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationProjetExport($realisationProjets_data), 'realisationProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationProjetExport($realisationProjets_data), 'realisationProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationProjets::realisationProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationProjets()
    {
        $realisationProjets = $this->realisationProjetService->all();
        return response()->json($realisationProjets);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $realisationProjet = $this->realisationProjetService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedRealisationProjet = $this->realisationProjetService->dataCalcul($realisationProjet);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedRealisationProjet
        ]);
    }
    


}
