<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgCreationProjet\Services\LivrableService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgCreationProjet\Services\ResourceService;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCreationProjet\App\Requests\ProjetRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\ProjetExport;
use Modules\PkgCreationProjet\App\Imports\ProjetImport;
use Modules\Core\Services\ContextState;

class BaseProjetController extends AdminController
{
    protected $projetService;
    protected $formateurService;

    public function __construct(ProjetService $projetService, FormateurService $formateurService) {
        parent::__construct();
        $this->projetService = $projetService;
        $this->formateurService = $formateurService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $projets_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('projets_search', '')],
            $request->except(['projets_search', 'page', 'sort'])
        );

        // Paginer les projets
        $projets_data = $this->projetService->paginate($projets_params);

        // Récupérer les statistiques et les champs filtrables
        $projets_stats = $this->projetService->getprojetStats();
        $projets_filters = $this->projetService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgCreationProjet::projet._table', compact('projets_data', 'projets_stats', 'projets_filters'))->render();
        }

        return view('PkgCreationProjet::projet.index', compact('projets_data', 'projets_stats', 'projets_filters'));
    }
    public function create() {
        $itemProjet = $this->projetService->createInstance();
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._fields', compact('itemProjet', 'formateurs'));
        }
        return view('PkgCreationProjet::projet.create', compact('itemProjet', 'formateurs'));
    }
    public function store(ProjetRequest $request) {
        $validatedData = $request->validated();
        $projet = $this->projetService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $projet,
                'modelName' => __('PkgCreationProjet::projet.singular')])
            ]);
        }

        return redirect()->route('projets.edit',['projet' => $projet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $projet,
                'modelName' => __('PkgCreationProjet::projet.singular')
            ])
        );
    }
    public function show(string $id) {
        $itemProjet = $this->projetService->find($id);
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._fields', compact('itemProjet', 'formateurs'));
        }

        return view('PkgCreationProjet::projet.show', compact('itemProjet'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('projet_id', $id);
        
        $itemProjet = $this->projetService->find($id);
        $formateurs = $this->formateurService->all();
        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_data =  $itemProjet->affectationProjets()->paginate(10);
        $affectationProjets_stats = $affectationProjetService->getaffectationProjetStats();
        $affectationProjets_filters = $affectationProjetService->getFieldsFilterable();
        
        $livrableService =  new LivrableService();
        $livrables_data =  $itemProjet->livrables()->paginate(10);
        $livrables_stats = $livrableService->getlivrableStats();
        $livrables_filters = $livrableService->getFieldsFilterable();
        
        $realisationProjetService =  new RealisationProjetService();
        $realisationProjets_data =  $itemProjet->realisationProjets()->paginate(10);
        $realisationProjets_stats = $realisationProjetService->getrealisationProjetStats();
        $realisationProjets_filters = $realisationProjetService->getFieldsFilterable();
        
        $resourceService =  new ResourceService();
        $resources_data =  $itemProjet->resources()->paginate(10);
        $resources_stats = $resourceService->getresourceStats();
        $resources_filters = $resourceService->getFieldsFilterable();
        
        $transfertCompetenceService =  new TransfertCompetenceService();
        $transfertCompetences_data =  $itemProjet->transfertCompetences()->paginate(10);
        $transfertCompetences_stats = $transfertCompetenceService->gettransfertCompetenceStats();
        $transfertCompetences_filters = $transfertCompetenceService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._fields', compact('itemProjet', 'formateurs', 'affectationProjets_data', 'livrables_data', 'realisationProjets_data', 'resources_data', 'transfertCompetences_data', 'affectationProjets_stats', 'livrables_stats', 'realisationProjets_stats', 'resources_stats', 'transfertCompetences_stats', 'affectationProjets_filters', 'livrables_filters', 'realisationProjets_filters', 'resources_filters', 'transfertCompetences_filters'));
        }

        return view('PkgCreationProjet::projet.edit', compact('itemProjet', 'formateurs', 'affectationProjets_data', 'livrables_data', 'realisationProjets_data', 'resources_data', 'transfertCompetences_data', 'affectationProjets_stats', 'livrables_stats', 'realisationProjets_stats', 'resources_stats', 'transfertCompetences_stats', 'affectationProjets_filters', 'livrables_filters', 'realisationProjets_filters', 'resources_filters', 'transfertCompetences_filters'));

    }
    public function update(ProjetRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $projet = $this->projetService->find($id);
        $this->authorize('update', $projet);

        $validatedData = $request->validated();
        $projet = $this->projetService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')])
            ]);
        }

        return redirect()->route('projets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $projet = $this->projetService->find($id);
        $this->authorize('delete', $projet);

        $projet = $this->projetService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')])
            ]);
        }

        return redirect()->route('projets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')
                ])
        );

    }

    public function export()
    {
        $projets_data = $this->projetService->all();
        return Excel::download(new ProjetExport($projets_data), 'projet_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('projets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('projets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::projet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getProjets()
    {
        $projets = $this->projetService->all();
        return response()->json($projets);
    }

}
