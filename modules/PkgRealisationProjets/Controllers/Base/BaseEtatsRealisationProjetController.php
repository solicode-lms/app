<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgRealisationProjets\App\Requests\EtatsRealisationProjetRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\EtatsRealisationProjetExport;
use Modules\PkgRealisationProjets\App\Imports\EtatsRealisationProjetImport;
use Modules\Core\Services\ContextState;

class BaseEtatsRealisationProjetController extends AdminController
{
    protected $etatsRealisationProjetService;
    protected $formateurService;

    public function __construct(EtatsRealisationProjetService $etatsRealisationProjetService, FormateurService $formateurService) {
        parent::__construct();
        $this->etatsRealisationProjetService = $etatsRealisationProjetService;
        $this->formateurService = $formateurService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $etatsRealisationProjets_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('etatsRealisationProjets_search', '')],
            $request->except(['etatsRealisationProjets_search', 'page', 'sort'])
        );

        // Paginer les etatsRealisationProjets
        $etatsRealisationProjets_data = $this->etatsRealisationProjetService->paginate($etatsRealisationProjets_params);

        // Récupérer les statistiques et les champs filtrables
        $etatsRealisationProjets_stats = $this->etatsRealisationProjetService->getetatsRealisationProjetStats();
        $etatsRealisationProjets_filters = $this->etatsRealisationProjetService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgRealisationProjets::etatsRealisationProjet._table', compact('etatsRealisationProjets_data', 'etatsRealisationProjets_stats', 'etatsRealisationProjets_filters'))->render();
        }

        return view('PkgRealisationProjets::etatsRealisationProjet.index', compact('etatsRealisationProjets_data', 'etatsRealisationProjets_stats', 'etatsRealisationProjets_filters'));
    }
    public function create() {
        $itemEtatsRealisationProjet = $this->etatsRealisationProjetService->createInstance();
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgRealisationProjets::etatsRealisationProjet._fields', compact('itemEtatsRealisationProjet', 'formateurs'));
        }
        return view('PkgRealisationProjets::etatsRealisationProjet.create', compact('itemEtatsRealisationProjet', 'formateurs'));
    }
    public function store(EtatsRealisationProjetRequest $request) {
        $validatedData = $request->validated();
        $etatsRealisationProjet = $this->etatsRealisationProjetService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'entity_id' => $etatsRealisationProjet->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' => __('PkgRealisationProjets::etatsRealisationProjet.singular')])
            ]);
        }

        return redirect()->route('etatsRealisationProjets.edit',['etatsRealisationProjet' => $etatsRealisationProjet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' => __('PkgRealisationProjets::etatsRealisationProjet.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('etats_realisation_projet_id', $id);
        
        $itemEtatsRealisationProjet = $this->etatsRealisationProjetService->find($id);
        $formateurs = $this->formateurService->all();
        $realisationProjetService =  new RealisationProjetService();
        $realisationProjets_data =  $itemEtatsRealisationProjet->realisationProjets()->paginate(10);
        $realisationProjets_stats = $realisationProjetService->getrealisationProjetStats();
        $realisationProjets_filters = $realisationProjetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgRealisationProjets::etatsRealisationProjet._edit', compact('itemEtatsRealisationProjet', 'formateurs', 'realisationProjets_data', 'realisationProjets_stats', 'realisationProjets_filters'));
        }

        return view('PkgRealisationProjets::etatsRealisationProjet.edit', compact('itemEtatsRealisationProjet', 'formateurs', 'realisationProjets_data', 'realisationProjets_stats', 'realisationProjets_filters'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('etats_realisation_projet_id', $id);
        
        $itemEtatsRealisationProjet = $this->etatsRealisationProjetService->find($id);
        $formateurs = $this->formateurService->all();
        $realisationProjetService =  new RealisationProjetService();
        $realisationProjets_data =  $itemEtatsRealisationProjet->realisationProjets()->paginate(10);
        $realisationProjets_stats = $realisationProjetService->getrealisationProjetStats();
        $realisationProjets_filters = $realisationProjetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgRealisationProjets::etatsRealisationProjet._edit', compact('itemEtatsRealisationProjet', 'formateurs', 'realisationProjets_data', 'realisationProjets_stats', 'realisationProjets_filters'));
        }

        return view('PkgRealisationProjets::etatsRealisationProjet.edit', compact('itemEtatsRealisationProjet', 'formateurs', 'realisationProjets_data', 'realisationProjets_stats', 'realisationProjets_filters'));

    }
    public function update(EtatsRealisationProjetRequest $request, string $id) {

        $validatedData = $request->validated();
        $etatsRealisationProjet = $this->etatsRealisationProjetService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' =>  __('PkgRealisationProjets::etatsRealisationProjet.singular')])
            ]);
        }

        return redirect()->route('etatsRealisationProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' =>  __('PkgRealisationProjets::etatsRealisationProjet.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $etatsRealisationProjet = $this->etatsRealisationProjetService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' =>  __('PkgRealisationProjets::etatsRealisationProjet.singular')])
            ]);
        }

        return redirect()->route('etatsRealisationProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' =>  __('PkgRealisationProjets::etatsRealisationProjet.singular')
                ])
        );

    }

    public function export()
    {
        $etatsRealisationProjets_data = $this->etatsRealisationProjetService->all();
        return Excel::download(new EtatsRealisationProjetExport($etatsRealisationProjets_data), 'etatsRealisationProjet_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new EtatsRealisationProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatsRealisationProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatsRealisationProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationProjets::etatsRealisationProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatsRealisationProjets()
    {
        $etatsRealisationProjets = $this->etatsRealisationProjetService->all();
        return response()->json($etatsRealisationProjets);
    }

}
