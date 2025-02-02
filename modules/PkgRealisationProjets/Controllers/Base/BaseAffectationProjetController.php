<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgFormation\Services\AnneeFormationService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgRealisationProjets\App\Requests\AffectationProjetRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\AffectationProjetExport;
use Modules\PkgRealisationProjets\App\Imports\AffectationProjetImport;
use Modules\Core\Services\ContextState;

class BaseAffectationProjetController extends AdminController
{
    protected $affectationProjetService;
    protected $anneeFormationService;
    protected $groupeService;
    protected $projetService;

    public function __construct(AffectationProjetService $affectationProjetService, AnneeFormationService $anneeFormationService, GroupeService $groupeService, ProjetService $projetService) {
        parent::__construct();
        $this->affectationProjetService = $affectationProjetService;
        $this->anneeFormationService = $anneeFormationService;
        $this->groupeService = $groupeService;
        $this->projetService = $projetService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $affectationProjets_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('affectationProjets_search', '')],
            $request->except(['affectationProjets_search', 'page', 'sort'])
        );

        // Paginer les affectationProjets
        $affectationProjets_data = $this->affectationProjetService->paginate($affectationProjets_params);

        // Récupérer les statistiques et les champs filtrables
        $affectationProjets_stats = $this->affectationProjetService->getaffectationProjetStats();
        $affectationProjets_filters = $this->affectationProjetService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgRealisationProjets::affectationProjet._table', compact('affectationProjets_data', 'affectationProjets_stats', 'affectationProjets_filters'))->render();
        }

        return view('PkgRealisationProjets::affectationProjet.index', compact('affectationProjets_data', 'affectationProjets_stats', 'affectationProjets_filters'));
    }
    public function create() {
        $itemAffectationProjet = $this->affectationProjetService->createInstance();
        $anneeFormations = $this->anneeFormationService->all();
        $groupes = $this->groupeService->all();
        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgRealisationProjets::affectationProjet._fields', compact('itemAffectationProjet', 'anneeFormations', 'groupes', 'projets'));
        }
        return view('PkgRealisationProjets::affectationProjet.create', compact('itemAffectationProjet', 'anneeFormations', 'groupes', 'projets'));
    }
    public function store(AffectationProjetRequest $request) {
        $validatedData = $request->validated();
        $affectationProjet = $this->affectationProjetService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'affectation_projet_id' => $affectationProjet->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' => __('PkgRealisationProjets::affectationProjet.singular')])
            ]);
        }

        return redirect()->route('affectationProjets.edit',['affectationProjet' => $affectationProjet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' => __('PkgRealisationProjets::affectationProjet.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('affectation_projet_id', $id);
        
        $itemAffectationProjet = $this->affectationProjetService->find($id);
        $anneeFormations = $this->anneeFormationService->all();
        $groupes = $this->groupeService->all();
        $projets = $this->projetService->all();
        $realisationProjetService =  new RealisationProjetService();
        $realisationProjets_data =  $itemAffectationProjet->realisationProjets()->paginate(10);
        $realisationProjets_stats = $realisationProjetService->getrealisationProjetStats();
        $realisationProjets_filters = $realisationProjetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgRealisationProjets::affectationProjet._fields', compact('itemAffectationProjet', 'anneeFormations', 'groupes', 'projets', 'realisationProjets_data', 'realisationProjets_stats', 'realisationProjets_filters'));
        }

        return view('PkgRealisationProjets::affectationProjet.edit', compact('itemAffectationProjet', 'anneeFormations', 'groupes', 'projets', 'realisationProjets_data', 'realisationProjets_stats', 'realisationProjets_filters'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('affectation_projet_id', $id);
        
        $itemAffectationProjet = $this->affectationProjetService->find($id);
        $anneeFormations = $this->anneeFormationService->all();
        $groupes = $this->groupeService->all();
        $projets = $this->projetService->all();
        $realisationProjetService =  new RealisationProjetService();
        $realisationProjets_data =  $itemAffectationProjet->realisationProjets()->paginate(10);
        $realisationProjets_stats = $realisationProjetService->getrealisationProjetStats();
        $realisationProjets_filters = $realisationProjetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgRealisationProjets::affectationProjet._fields', compact('itemAffectationProjet', 'anneeFormations', 'groupes', 'projets', 'realisationProjets_data', 'realisationProjets_stats', 'realisationProjets_filters'));
        }

        return view('PkgRealisationProjets::affectationProjet.edit', compact('itemAffectationProjet', 'anneeFormations', 'groupes', 'projets', 'realisationProjets_data', 'realisationProjets_stats', 'realisationProjets_filters'));

    }
    public function update(AffectationProjetRequest $request, string $id) {

        $validatedData = $request->validated();
        $affectationProjet = $this->affectationProjetService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' =>  __('PkgRealisationProjets::affectationProjet.singular')])
            ]);
        }

        return redirect()->route('affectationProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' =>  __('PkgRealisationProjets::affectationProjet.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $affectationProjet = $this->affectationProjetService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' =>  __('PkgRealisationProjets::affectationProjet.singular')])
            ]);
        }

        return redirect()->route('affectationProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' =>  __('PkgRealisationProjets::affectationProjet.singular')
                ])
        );

    }

    public function export()
    {
        $affectationProjets_data = $this->affectationProjetService->all();
        return Excel::download(new AffectationProjetExport($affectationProjets_data), 'affectationProjet_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new AffectationProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('affectationProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('affectationProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationProjets::affectationProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getAffectationProjets()
    {
        $affectationProjets = $this->affectationProjetService->all();
        return response()->json($affectationProjets);
    }

}
