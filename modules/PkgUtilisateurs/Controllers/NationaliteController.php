<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\NationaliteRequest;
use Modules\PkgUtilisateurs\Services\NationaliteService;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\NationaliteExport;
use Modules\PkgUtilisateurs\App\Imports\NationaliteImport;

class NationaliteController extends AdminController
{
    protected $nationaliteService;

    public function __construct(NationaliteService $nationaliteService)
    {
        parent::__construct();
        $this->nationaliteService = $nationaliteService;
    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $data = $this->nationaliteService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('PkgUtilisateurs::nationalite._table', compact('data'))->render();
        }

        return view('PkgUtilisateurs::nationalite.index', compact('data','searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemNationalite = $this->nationaliteService->createInstance();

        if (request()->ajax()) {
            return view('PkgUtilisateurs::nationalite._fields', compact('itemNationalite'));
        }
        return view('PkgUtilisateurs::nationalite.create', compact('itemNationalite'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(NationaliteRequest $request)
    {
        $validatedData = $request->validated();
        $nationalite = $this->nationaliteService->create($validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $nationalite,
                'modelName' => __('PkgUtilisateurs::nationalite.singular')])
            ]);
        }

        return redirect()->route('nationalites.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $nationalite,
                'modelName' => __('PkgUtilisateurs::nationalite.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemNationalite = $this->nationaliteService->find($id);

        if (request()->ajax()) {
            return view('PkgUtilisateurs::nationalite._fields', compact('itemNationalite'));
        }

        return view('PkgUtilisateurs::nationalite.show', compact('itemNationalite'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemNationalite = $this->nationaliteService->find($id);

        if (request()->ajax()) {
            return view('PkgUtilisateurs::nationalite._fields', compact('itemNationalite'));
        }

        return view('PkgUtilisateurs::nationalite.edit', compact('itemNationalite'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(NationaliteRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $nationalite = $this->nationaliteService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgUtilisateurs::nationalite.singular')])
            ]);
        }

        return redirect()->route('nationalites.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgUtilisateurs::nationalite.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $nationalite = $this->nationaliteService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgUtilisateurs::nationalite.singular')])
            ]);
        }

        return redirect()->route('nationalites.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $nationalite,
                'modelName' =>  __('PkgUtilisateurs::nationalite.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->nationaliteService->all();
        return Excel::download(new NationaliteExport($data), 'nationalite_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new NationaliteImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('nationalites.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('nationalites.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgUtilisateurs::nationalite.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNationalites()
    {
        $nationalites = $this->nationaliteService->all();
        return response()->json($nationalites);
    }
}
