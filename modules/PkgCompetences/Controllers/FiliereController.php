<?php

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
     * Index : Afficher la liste des filières ou le HTML pour AJAX.
     */
    public function index(Request $request)
    {
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
        $data = $this->filiereService->paginate($searchQuery);

        // Réponse AJAX
        if ($request->ajax()) {
            return view('PkgCompetences::filiere._table', compact('data'))->render();
        }

        // Create form modal
        $itemFiliere = $this->filiereService->createInstance();

        // Chargement initial
        return view('PkgCompetences::filiere.index', compact('data','itemFiliere'));
    }

    /**
     * Création : Renvoie le formulaire pour l'ajout.
     */
    public function create()
    {
        $itemFiliere = $this->filiereService->createInstance();

        // Réponse pour AJAX
        if (request()->ajax()) {
            return view('PkgCompetences::filiere._fields', compact('itemFiliere'));
        }


        return view('PkgCompetences::filiere.create', compact('itemFiliere'));
    }

    /**
     * Stocker une nouvelle filière.
     */
    public function store(FiliereRequest $request)
    {
        $validatedData = $request->validated();
        $filiere = $this->filiereService->create($validatedData);

        // Réponse pour AJAX
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('Filière ajoutée avec succès !')]);
        }

        return redirect()->route('filieres.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $filiere,
            'modelName' => __('PkgCompetences::filiere.singular')
        ]));
    }

    /**
     * Afficher les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemFiliere = $this->filiereService->find($id);

        // Réponse pour AJAX
        if (request()->ajax()) {
            return view('PkgCompetences::filiere._fields', compact('itemFiliere'));
        }

        return view('PkgCompetences::filiere.show', compact('itemFiliere'));
    }


   



    /**
     * Éditer une filière existante.
     */
    public function edit(string $id)
    {
        $itemFiliere = $this->filiereService->find($id);
        
        // Réponse pour AJAX
        if (request()->ajax()) {
            return view('PkgCompetences::filiere._fields', compact('itemFiliere'));
        }
        
        return view('PkgCompetences::filiere.edit', compact('itemFiliere'));
    }

    /**
     * Mettre à jour une filière existante.
     */
    public function update(FiliereRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $filiere = $this->filiereService->update($id, $validatedData);

        // Réponse pour AJAX
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('Filière mise à jour avec succès !')]);
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
     * Supprimer une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $filiere = $this->filiereService->destroy($id);

        // Réponse pour AJAX
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('Filière supprimée avec succès !')]);
        }

        return redirect()->route('filieres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $filiere,
                'modelName' =>  __('PkgCompetences::filiere.singular')
            ])
        );
    }

    /**
     * Exporter les filières vers un fichier Excel.
     */
    public function export()
    {
        $data = $this->filiereService->all();
        return Excel::download(new FiliereExport($data), 'filiere_export.xlsx');
    }

    /**
     * Importer des filières depuis un fichier Excel.
     */
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
            ])
        );
    }

    /**
     * Fournir une liste des filières en JSON (utilisation pour JavaScript).
     */
    public function getFilieres()
    {
        $filieres = $this->filiereService->all();
        return response()->json($filieres);
    }
}
