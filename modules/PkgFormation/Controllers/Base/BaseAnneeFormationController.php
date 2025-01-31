<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\AnneeFormationService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgApprenants\Services\GroupeService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgFormation\App\Requests\AnneeFormationRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgFormation\App\Exports\AnneeFormationExport;
use Modules\PkgFormation\App\Imports\AnneeFormationImport;
use Modules\Core\Services\ContextState;

class BaseAnneeFormationController extends AdminController
{
    protected $anneeFormationService;

    public function __construct(AnneeFormationService $anneeFormationService) {
        parent::__construct();
        $this->anneeFormationService = $anneeFormationService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $anneeFormations_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('anneeFormations_search', '')],
            $request->except(['anneeFormations_search', 'page', 'sort'])
        );

        // Paginer les anneeFormations
        $anneeFormations_data = $this->anneeFormationService->paginate($anneeFormations_params);

        // Récupérer les statistiques et les champs filtrables
        $anneeFormations_stats = $this->anneeFormationService->getanneeFormationStats();
        $anneeFormations_filters = $this->anneeFormationService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgFormation::anneeFormation._table', compact('anneeFormations_data', 'anneeFormations_stats', 'anneeFormations_filters'))->render();
        }

        return view('PkgFormation::anneeFormation.index', compact('anneeFormations_data', 'anneeFormations_stats', 'anneeFormations_filters'));
    }
    public function create() {
        $itemAnneeFormation = $this->anneeFormationService->createInstance();


        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._fields', compact('itemAnneeFormation'));
        }
        return view('PkgFormation::anneeFormation.create', compact('itemAnneeFormation'));
    }
    public function store(AnneeFormationRequest $request) {
        $validatedData = $request->validated();
        $anneeFormation = $this->anneeFormationService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'annee_formation_id' => $anneeFormation->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' => __('PkgFormation::anneeFormation.singular')])
            ]);
        }

        return redirect()->route('anneeFormations.edit',['anneeFormation' => $anneeFormation->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' => __('PkgFormation::anneeFormation.singular')
            ])
        );
    }
    public function show(string $id) {
        $itemAnneeFormation = $this->anneeFormationService->find($id);


        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._fields', compact('itemAnneeFormation'));
        }

        return view('PkgFormation::anneeFormation.show', compact('itemAnneeFormation'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('annee_formation_id', $id);
        
        $itemAnneeFormation = $this->anneeFormationService->find($id);
        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_data =  $itemAnneeFormation->affectationProjets()->paginate(10);
        $affectationProjets_stats = $affectationProjetService->getaffectationProjetStats();
        $affectationProjets_filters = $affectationProjetService->getFieldsFilterable();
        
        $groupeService =  new GroupeService();
        $groupes_data =  $itemAnneeFormation->groupes()->paginate(10);
        $groupes_stats = $groupeService->getgroupeStats();
        $groupes_filters = $groupeService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._fields', compact('itemAnneeFormation', 'affectationProjets_data', 'groupes_data', 'affectationProjets_stats', 'groupes_stats', 'affectationProjets_filters', 'groupes_filters'));
        }

        return view('PkgFormation::anneeFormation.edit', compact('itemAnneeFormation', 'affectationProjets_data', 'groupes_data', 'affectationProjets_stats', 'groupes_stats', 'affectationProjets_filters', 'groupes_filters'));

    }
    public function update(AnneeFormationRequest $request, string $id) {

        $validatedData = $request->validated();
        $anneeFormation = $this->anneeFormationService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')])
            ]);
        }

        return redirect()->route('anneeFormations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $anneeFormation = $this->anneeFormationService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')])
            ]);
        }

        return redirect()->route('anneeFormations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')
                ])
        );

    }

    public function export()
    {
        $anneeFormations_data = $this->anneeFormationService->all();
        return Excel::download(new AnneeFormationExport($anneeFormations_data), 'anneeFormation_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new AnneeFormationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('anneeFormations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('anneeFormations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgFormation::anneeFormation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getAnneeFormations()
    {
        $anneeFormations = $this->anneeFormationService->all();
        return response()->json($anneeFormations);
    }

}
