<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\CompetenceRequest;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgCompetences\Services\TechnologyService;
use Modules\PkgCompetences\Services\ModuleService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\CompetenceExport;
use Modules\PkgCompetences\App\Imports\CompetenceImport;
use Modules\Core\Services\ContextState;

class BaseCompetenceController extends AdminController
{
    protected $competenceService;
    protected $technologyService;
    protected $moduleService;

    public function __construct(CompetenceService $competenceService, TechnologyService $technologyService, ModuleService $moduleService)
    {
        parent::__construct();
        $this->competenceService = $competenceService;
        $this->technologyService = $technologyService;
        $this->moduleService = $moduleService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $competence_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $competences_data = $this->competenceService->paginate($competence_searchQuery);

        if ($request->ajax()) {
            return view('PkgCompetences::competence._table', compact('competences_data'))->render();
        }

        return view('PkgCompetences::competence.index', compact('competences_data','competence_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemCompetence = $this->competenceService->createInstance();
        $technologies = $this->technologyService->all();
        $modules = $this->moduleService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::competence._fields', compact('itemCompetence', 'technologies', 'modules'));
        }
        return view('PkgCompetences::competence.create', compact('itemCompetence', 'technologies', 'modules'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(CompetenceRequest $request)
    {
        $validatedData = $request->validated();
        $competence = $this->competenceService->create($validatedData);


        if ($request->has('technologies')) {
            $competence->technologies()->sync($request->input('technologies'));
        }


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $competence,
                'modelName' => __('PkgCompetences::competence.singular')])
            ]);
        }

        return redirect()->route('competences.edit',['competence' => $competence->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $competence,
                'modelName' => __('PkgCompetences::competence.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemCompetence = $this->competenceService->find($id);
        $technologies = $this->technologyService->all();
        $modules = $this->moduleService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::competence._fields', compact('itemCompetence', 'technologies', 'modules'));
        }

        return view('PkgCompetences::competence.show', compact('itemCompetence'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemCompetence = $this->competenceService->find($id);
        $technologies = $this->technologyService->all();
        $modules = $this->moduleService->all();
         $niveauCompetences_data =  $itemCompetence->niveauCompetences()->paginate(10);

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('competence_id', $id);


        if (request()->ajax()) {
            return view('PkgCompetences::competence._fields', compact('itemCompetence', 'technologies', 'modules', 'niveauCompetences_data'));
        }

        return view('PkgCompetences::competence.edit', compact('itemCompetence', 'technologies', 'modules', 'niveauCompetences_data'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(CompetenceRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $competence = $this->competenceService->update($id, $validatedData);


        $competence->technologies()->sync($request->input('technologies'));


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')])
            ]);
        }

        return redirect()->route('competences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $competence = $this->competenceService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')])
            ]);
        }

        return redirect()->route('competences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $competence,
                'modelName' =>  __('PkgCompetences::competence.singular')
                ])
        );
    }

    public function export()
    {
        $competences_data = $this->competenceService->all();
        return Excel::download(new CompetenceExport($competences_data), 'competence_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new CompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('competences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('competences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::competence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getCompetences()
    {
        $competences = $this->competenceService->all();
        return response()->json($competences);
    }
}
