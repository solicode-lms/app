<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgFormation\Services\AnneeFormationService;
use Modules\PkgFormation\Services\FiliereService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgApprenants\App\Requests\GroupeRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprenants\App\Exports\GroupeExport;
use Modules\PkgApprenants\App\Imports\GroupeImport;
use Modules\Core\Services\ContextState;

class BaseGroupeController extends AdminController
{
    protected $groupeService;
    protected $apprenantService;
    protected $formateurService;
    protected $anneeFormationService;
    protected $filiereService;

    public function __construct(GroupeService $groupeService, ApprenantService $apprenantService, FormateurService $formateurService, AnneeFormationService $anneeFormationService, FiliereService $filiereService) {
        parent::__construct();
        $this->groupeService = $groupeService;
        $this->apprenantService = $apprenantService;
        $this->formateurService = $formateurService;
        $this->anneeFormationService = $anneeFormationService;
        $this->filiereService = $filiereService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $groupes_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('groupes_search', '')],
            $request->except(['groupes_search', 'page', 'sort'])
        );

        // Paginer les groupes
        $groupes_data = $this->groupeService->paginate($groupes_params);

        // Récupérer les statistiques et les champs filtrables
        $groupes_stats = $this->groupeService->getgroupeStats();
        $groupes_filters = $this->groupeService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgApprenants::groupe._table', compact('groupes_data', 'groupes_stats', 'groupes_filters'))->render();
        }

        return view('PkgApprenants::groupe.index', compact('groupes_data', 'groupes_stats', 'groupes_filters'));
    }
    public function create() {
        $itemGroupe = $this->groupeService->createInstance();
        $apprenants = $this->apprenantService->all();
        $formateurs = $this->formateurService->all();
        $anneeFormations = $this->anneeFormationService->all();
        $filieres = $this->filiereService->all();


        if (request()->ajax()) {
            return view('PkgApprenants::groupe._fields', compact('itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres'));
        }
        return view('PkgApprenants::groupe.create', compact('itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres'));
    }
    public function store(GroupeRequest $request) {
        $validatedData = $request->validated();
        $groupe = $this->groupeService->create($validatedData);


        if ($request->has('apprenants')) {
            $groupe->apprenants()->sync($request->input('apprenants'));
        }
        if ($request->has('formateurs')) {
            $groupe->formateurs()->sync($request->input('formateurs'));
        }


        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'groupe_id' => $groupe->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $groupe,
                'modelName' => __('PkgApprenants::groupe.singular')])
            ]);
        }

        return redirect()->route('groupes.edit',['groupe' => $groupe->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $groupe,
                'modelName' => __('PkgApprenants::groupe.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('groupe_id', $id);
        
        $itemGroupe = $this->groupeService->find($id);
        $apprenants = $this->apprenantService->all();
        $formateurs = $this->formateurService->all();
        $anneeFormations = $this->anneeFormationService->all();
        $filieres = $this->filiereService->all();
        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_data =  $itemGroupe->affectationProjets()->paginate(10);
        $affectationProjets_stats = $affectationProjetService->getaffectationProjetStats();
        $affectationProjets_filters = $affectationProjetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgApprenants::groupe._edit', compact('itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres', 'affectationProjets_data', 'affectationProjets_stats', 'affectationProjets_filters'));
        }

        return view('PkgApprenants::groupe.edit', compact('itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres', 'affectationProjets_data', 'affectationProjets_stats', 'affectationProjets_filters'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('groupe_id', $id);
        
        $itemGroupe = $this->groupeService->find($id);
        $apprenants = $this->apprenantService->all();
        $formateurs = $this->formateurService->all();
        $anneeFormations = $this->anneeFormationService->all();
        $filieres = $this->filiereService->all();
        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_data =  $itemGroupe->affectationProjets()->paginate(10);
        $affectationProjets_stats = $affectationProjetService->getaffectationProjetStats();
        $affectationProjets_filters = $affectationProjetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgApprenants::groupe._edit', compact('itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres', 'affectationProjets_data', 'affectationProjets_stats', 'affectationProjets_filters'));
        }

        return view('PkgApprenants::groupe.edit', compact('itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres', 'affectationProjets_data', 'affectationProjets_stats', 'affectationProjets_filters'));

    }
    public function update(GroupeRequest $request, string $id) {

        $validatedData = $request->validated();
        $groupe = $this->groupeService->update($id, $validatedData);

        $groupe->apprenants()->sync($request->input('apprenants'));
        $groupe->formateurs()->sync($request->input('formateurs'));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgApprenants::groupe.singular')])
            ]);
        }

        return redirect()->route('groupes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgApprenants::groupe.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $groupe = $this->groupeService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgApprenants::groupe.singular')])
            ]);
        }

        return redirect()->route('groupes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgApprenants::groupe.singular')
                ])
        );

    }

    public function export()
    {
        $groupes_data = $this->groupeService->all();
        return Excel::download(new GroupeExport($groupes_data), 'groupe_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new GroupeImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('groupes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('groupes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprenants::groupe.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getGroupes()
    {
        $groupes = $this->groupeService->all();
        return response()->json($groupes);
    }

}
