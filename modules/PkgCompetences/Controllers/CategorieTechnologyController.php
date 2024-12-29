<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\CategorieTechnologyRequest;
use Modules\PkgCompetences\Services\CategorieTechnologyService;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\CategorieTechnologyExport;
use Modules\PkgCompetences\App\Imports\CategorieTechnologyImport;

class CategorieTechnologyController extends AdminController
{
    protected $categorieTechnologyService;

    public function __construct(CategorieTechnologyService $categorieTechnologyService)
    {
        parent::__construct();
        $this->categorieTechnologyService = $categorieTechnologyService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $categorieTechnology_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $categorieTechnologies_data = $this->categorieTechnologyService->paginate($categorieTechnology_searchQuery);

        if ($request->ajax()) {
            return view('PkgCompetences::categorieTechnology._table', compact('categorieTechnologies_data'))->render();
        }

        return view('PkgCompetences::categorieTechnology.index', compact('categorieTechnologies_data','categorieTechnology_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemCategorieTechnology = $this->categorieTechnologyService->createInstance();


        if (request()->ajax()) {
            return view('PkgCompetences::categorieTechnology._fields', compact('itemCategorieTechnology'));
        }
        return view('PkgCompetences::categorieTechnology.create', compact('itemCategorieTechnology'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(CategorieTechnologyRequest $request)
    {
        $validatedData = $request->validated();
        $categorieTechnology = $this->categorieTechnologyService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $categorieTechnology,
                'modelName' => __('PkgCompetences::categorieTechnology.singular')])
            ]);
        }

        return redirect()->route('categorieTechnologys.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $categorieTechnology,
                'modelName' => __('PkgCompetences::categorieTechnology.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemCategorieTechnology = $this->categorieTechnologyService->find($id);


        if (request()->ajax()) {
            return view('PkgCompetences::categorieTechnology._fields', compact('itemCategorieTechnology'));
        }

        return view('PkgCompetences::categorietechnology.show', compact('itemCategorieTechnology'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemCategorieTechnology = $this->categorieTechnologyService->find($id);

        if (request()->ajax()) {
            return view('PkgCompetences::categorieTechnology._fields', compact('itemCategorieTechnology'));
        }

        return view('PkgCompetences::categorieTechnology.edit', compact('itemCategorieTechnology'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(CategorieTechnologyRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $categorietechnology = $this->categorieTechnologyService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $categorietechnology,
                'modelName' =>  __('PkgCompetences::categorietechnology.singular')])
            ]);
        }

        return redirect()->route('categorieTechnologies.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $categorietechnology,
                'modelName' =>  __('PkgCompetences::categorietechnology.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $categorietechnology = $this->categorieTechnologyService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $categorietechnology,
                'modelName' =>  __('PkgCompetences::categorietechnology.singular')])
            ]);
        }

        return redirect()->route('categorieTechnologies.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $categorietechnology,
                'modelName' =>  __('PkgCompetences::categorietechnology.singular')
                ])
        );
    }

    public function export()
    {
        $categorieTechnologies_data = $this->categorieTechnologyService->all();
        return Excel::download(new CategorieTechnologyExport($categorieTechnologies_data), 'categorieTechnology_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new CategorieTechnologyImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('categorieTechnologies.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('categorieTechnologies.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::categorietechnology.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getCategorieTechnologies()
    {
        $categorieTechnologies = $this->categorieTechnologyService->all();
        return response()->json($categorieTechnologies);
    }
}
