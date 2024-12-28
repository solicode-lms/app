<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\FiliereRequest;
use Modules\PkgCompetences\Services\FiliereService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\FiliereExport;
use Modules\PkgCompetences\App\Imports\FiliereImport;

class FiliereController extends AdminController
{
    protected $filiereService;

    public function __construct(FiliereService $filiereService)
    {
        parent::__construct();
        $this->filiereService = $filiereService;
    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $data = $this->filiereService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('PkgCompetences::filiere._table', compact('data'))->render();
        }

        return view('PkgCompetences::filiere.index', compact('data','searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemFiliere = $this->filiereService->createInstance();

        if (request()->ajax()) {
            return view('PkgCompetences::filiere._fields', compact('itemFiliere'));
        }
        return view('PkgCompetences::filiere.create', compact('itemFiliere'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(FiliereRequest $request)
    {
        $validatedData = $request->validated();
        $filiere = $this->filiereService->create($validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $filiere,
                'modelName' => __('PkgCompetences::filiere.singular')])
            ]);
        }

        return redirect()->route('filieres.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $filiere,
                'modelName' => __('PkgCompetences::filiere.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemFiliere = $this->filiereService->find($id);

        if (request()->ajax()) {
            return view('PkgCompetences::filiere._fields', compact('itemFiliere'));
        }

        return view('PkgCompetences::filiere.show', compact('itemFiliere'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemFiliere = $this->filiereService->find($id);

        if (request()->ajax()) {
            return view('PkgCompetences::filiere._fields', compact('itemFiliere'));
        }

        return view('PkgCompetences::filiere.edit', compact('itemFiliere'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(FiliereRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $filiere = $this->filiereService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgCompetences::filiere.singular')])
            ]);
        }

        return redirect()->route('filieres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgCompetences::filiere.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $filiere = $this->filiereService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgCompetences::filiere.singular')])
            ]);
        }

        return redirect()->route('filieres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgCompetences::filiere.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->filiereService->all();
        return Excel::download(new FiliereExport($data), 'filiere_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new FiliereImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('filieres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('filieres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::filiere.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFilieres()
    {
        $filieres = $this->filiereService->all();
        return response()->json($filieres);
    }
}
