<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\NiveauCompetenceRequest;
use Modules\PkgCompetences\Services\NiveauCompetenceService;
use Modules\PkgCompetences\Services\CompetenceService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\NiveauCompetenceExport;
use Modules\PkgCompetences\App\Imports\NiveauCompetenceImport;

class NiveauCompetenceController extends AdminController
{
    protected $niveauCompetenceService;
    protected $competenceService;

    public function __construct(NiveauCompetenceService $niveauCompetenceService, CompetenceService $competenceService)
    {
        parent::__construct();
        $this->niveauCompetenceService = $niveauCompetenceService;
        $this->competenceService = $competenceService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $data = $this->niveauCompetenceService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('PkgCompetences::niveauCompetence._table', compact('data'))->render();
        }

        return view('PkgCompetences::niveauCompetence.index', compact('data','searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemNiveauCompetence = $this->niveauCompetenceService->createInstance();
        $competences = $this->competenceService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::niveauCompetence._fields', compact('itemNiveauCompetence', 'competences'));
        }
        return view('PkgCompetences::niveauCompetence.create', compact('itemNiveauCompetence', 'competences'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(NiveauCompetenceRequest $request)
    {
        $validatedData = $request->validated();
        $niveauCompetence = $this->niveauCompetenceService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' => __('PkgCompetences::niveauCompetence.singular')])
            ]);
        }

        return redirect()->route('niveauCompetences.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' => __('PkgCompetences::niveauCompetence.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemNiveauCompetence = $this->niveauCompetenceService->find($id);
        $competences = $this->competenceService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::niveauCompetence._fields', compact('itemNiveauCompetence', 'competences'));
        }

        return view('PkgCompetences::niveaucompetence.show', compact('itemNiveauCompetence'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemNiveauCompetence = $this->niveauCompetenceService->find($id);
        $competences = $this->competenceService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::niveauCompetence._fields', compact('itemNiveauCompetence', 'competences'));
        }

        return view('PkgCompetences::niveauCompetence.edit', compact('itemNiveauCompetence', 'competences'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(NiveauCompetenceRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $niveaucompetence = $this->niveauCompetenceService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $niveaucompetence,
                'modelName' =>  __('PkgCompetences::niveaucompetence.singular')])
            ]);
        }

        return redirect()->route('niveauCompetences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $niveaucompetence,
                'modelName' =>  __('PkgCompetences::niveaucompetence.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $niveaucompetence = $this->niveauCompetenceService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $niveaucompetence,
                'modelName' =>  __('PkgCompetences::niveaucompetence.singular')])
            ]);
        }

        return redirect()->route('niveauCompetences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $niveaucompetence,
                'modelName' =>  __('PkgCompetences::niveaucompetence.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->niveauCompetenceService->all();
        return Excel::download(new NiveauCompetenceExport($data), 'niveauCompetence_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new NiveauCompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('niveauCompetences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('niveauCompetences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::niveaucompetence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNiveauCompetences()
    {
        $niveauCompetences = $this->niveauCompetenceService->all();
        return response()->json($niveauCompetences);
    }
}
