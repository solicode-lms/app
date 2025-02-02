<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgFormation\Services\SpecialiteService;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService;
use Modules\PkgCompetences\Services\NiveauDifficulteService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgFormation\App\Requests\FormateurRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgFormation\App\Exports\FormateurExport;
use Modules\PkgFormation\App\Imports\FormateurImport;
use Modules\Core\Services\ContextState;

class BaseFormateurController extends AdminController
{
    protected $formateurService;
    protected $groupeService;
    protected $specialiteService;
    protected $userService;

    public function __construct(FormateurService $formateurService, GroupeService $groupeService, SpecialiteService $specialiteService, UserService $userService) {
        parent::__construct();
        $this->formateurService = $formateurService;
        $this->groupeService = $groupeService;
        $this->specialiteService = $specialiteService;
        $this->userService = $userService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $formateurs_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('formateurs_search', '')],
            $request->except(['formateurs_search', 'page', 'sort'])
        );

        // Paginer les formateurs
        $formateurs_data = $this->formateurService->paginate($formateurs_params);

        // Récupérer les statistiques et les champs filtrables
        $formateurs_stats = $this->formateurService->getformateurStats();
        $formateurs_filters = $this->formateurService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgFormation::formateur._table', compact('formateurs_data', 'formateurs_stats', 'formateurs_filters'))->render();
        }

        return view('PkgFormation::formateur.index', compact('formateurs_data', 'formateurs_stats', 'formateurs_filters'));
    }
    public function create() {
        $itemFormateur = $this->formateurService->createInstance();
        $groupes = $this->groupeService->all();
        $specialites = $this->specialiteService->all();
        $users = $this->userService->all();


        if (request()->ajax()) {
            return view('PkgFormation::formateur._fields', compact('itemFormateur', 'groupes', 'specialites', 'users'));
        }
        return view('PkgFormation::formateur.create', compact('itemFormateur', 'groupes', 'specialites', 'users'));
    }
    public function store(FormateurRequest $request) {
        $validatedData = $request->validated();
        $formateur = $this->formateurService->create($validatedData);


        if ($request->has('groupes')) {
            $formateur->groupes()->sync($request->input('groupes'));
        }
        if ($request->has('specialites')) {
            $formateur->specialites()->sync($request->input('specialites'));
        }


        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'formateur_id' => $formateur->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $formateur,
                'modelName' => __('PkgFormation::formateur.singular')])
            ]);
        }

        return redirect()->route('formateurs.edit',['formateur' => $formateur->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $formateur,
                'modelName' => __('PkgFormation::formateur.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('formateur_id', $id);
        
        $itemFormateur = $this->formateurService->find($id);
        $groupes = $this->groupeService->all();
        $specialites = $this->specialiteService->all();
        $users = $this->userService->all();
        $etatsRealisationProjetService =  new EtatsRealisationProjetService();
        $etatsRealisationProjets_data =  $itemFormateur->etatsRealisationProjets()->paginate(10);
        $etatsRealisationProjets_stats = $etatsRealisationProjetService->getetatsRealisationProjetStats();
        $etatsRealisationProjets_filters = $etatsRealisationProjetService->getFieldsFilterable();
        
        $niveauDifficulteService =  new NiveauDifficulteService();
        $niveauDifficultes_data =  $itemFormateur->niveauDifficultes()->paginate(10);
        $niveauDifficultes_stats = $niveauDifficulteService->getniveauDifficulteStats();
        $niveauDifficultes_filters = $niveauDifficulteService->getFieldsFilterable();
        
        $projetService =  new ProjetService();
        $projets_data =  $itemFormateur->projets()->paginate(10);
        $projets_stats = $projetService->getprojetStats();
        $projets_filters = $projetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgFormation::formateur._fields', compact('itemFormateur', 'groupes', 'specialites', 'users', 'etatsRealisationProjets_data', 'niveauDifficultes_data', 'projets_data', 'etatsRealisationProjets_stats', 'niveauDifficultes_stats', 'projets_stats', 'etatsRealisationProjets_filters', 'niveauDifficultes_filters', 'projets_filters'));
        }

        return view('PkgFormation::formateur.edit', compact('itemFormateur', 'groupes', 'specialites', 'users', 'etatsRealisationProjets_data', 'niveauDifficultes_data', 'projets_data', 'etatsRealisationProjets_stats', 'niveauDifficultes_stats', 'projets_stats', 'etatsRealisationProjets_filters', 'niveauDifficultes_filters', 'projets_filters'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('formateur_id', $id);
        
        $itemFormateur = $this->formateurService->find($id);
        $groupes = $this->groupeService->all();
        $specialites = $this->specialiteService->all();
        $users = $this->userService->all();
        $etatsRealisationProjetService =  new EtatsRealisationProjetService();
        $etatsRealisationProjets_data =  $itemFormateur->etatsRealisationProjets()->paginate(10);
        $etatsRealisationProjets_stats = $etatsRealisationProjetService->getetatsRealisationProjetStats();
        $etatsRealisationProjets_filters = $etatsRealisationProjetService->getFieldsFilterable();
        
        $niveauDifficulteService =  new NiveauDifficulteService();
        $niveauDifficultes_data =  $itemFormateur->niveauDifficultes()->paginate(10);
        $niveauDifficultes_stats = $niveauDifficulteService->getniveauDifficulteStats();
        $niveauDifficultes_filters = $niveauDifficulteService->getFieldsFilterable();
        
        $projetService =  new ProjetService();
        $projets_data =  $itemFormateur->projets()->paginate(10);
        $projets_stats = $projetService->getprojetStats();
        $projets_filters = $projetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgFormation::formateur._fields', compact('itemFormateur', 'groupes', 'specialites', 'users', 'etatsRealisationProjets_data', 'niveauDifficultes_data', 'projets_data', 'etatsRealisationProjets_stats', 'niveauDifficultes_stats', 'projets_stats', 'etatsRealisationProjets_filters', 'niveauDifficultes_filters', 'projets_filters'));
        }

        return view('PkgFormation::formateur.edit', compact('itemFormateur', 'groupes', 'specialites', 'users', 'etatsRealisationProjets_data', 'niveauDifficultes_data', 'projets_data', 'etatsRealisationProjets_stats', 'niveauDifficultes_stats', 'projets_stats', 'etatsRealisationProjets_filters', 'niveauDifficultes_filters', 'projets_filters'));

    }
    public function update(FormateurRequest $request, string $id) {

        $validatedData = $request->validated();
        $formateur = $this->formateurService->update($id, $validatedData);

        $formateur->groupes()->sync($request->input('groupes'));
        $formateur->specialites()->sync($request->input('specialites'));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgFormation::formateur.singular')])
            ]);
        }

        return redirect()->route('formateurs.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgFormation::formateur.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $formateur = $this->formateurService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgFormation::formateur.singular')])
            ]);
        }

        return redirect()->route('formateurs.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgFormation::formateur.singular')
                ])
        );

    }

    public function export()
    {
        $formateurs_data = $this->formateurService->all();
        return Excel::download(new FormateurExport($formateurs_data), 'formateur_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new FormateurImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('formateurs.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('formateurs.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgFormation::formateur.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFormateurs()
    {
        $formateurs = $this->formateurService->all();
        return response()->json($formateurs);
    }

}
