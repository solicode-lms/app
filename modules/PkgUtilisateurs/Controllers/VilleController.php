<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\VilleRequest;
use Modules\PkgUtilisateurs\Services\VilleService;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\VilleExport;
use Modules\PkgUtilisateurs\App\Imports\VilleImport;
use Modules\Core\Services\ContextState;

class VilleController extends AdminController
{
    protected $villeService;

    public function __construct(VilleService $villeService)
    {
        parent::__construct();
        $this->villeService = $villeService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $ville_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $villes_data = $this->villeService->paginate($ville_searchQuery);

        if ($request->ajax()) {
            return view('PkgUtilisateurs::ville._table', compact('villes_data'))->render();
        }

        return view('PkgUtilisateurs::ville.index', compact('villes_data','ville_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemVille = $this->villeService->createInstance();


        if (request()->ajax()) {
            return view('PkgUtilisateurs::ville._fields', compact('itemVille'));
        }
        return view('PkgUtilisateurs::ville.create', compact('itemVille'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(VilleRequest $request)
    {
        $validatedData = $request->validated();
        $ville = $this->villeService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $ville,
                'modelName' => __('PkgUtilisateurs::ville.singular')])
            ]);
        }

        return redirect()->route('villes.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $ville,
                'modelName' => __('PkgUtilisateurs::ville.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemVille = $this->villeService->find($id);


        if (request()->ajax()) {
            return view('PkgUtilisateurs::ville._fields', compact('itemVille'));
        }

        return view('PkgUtilisateurs::ville.show', compact('itemVille'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemVille = $this->villeService->find($id);

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('ville_id', $id);


        if (request()->ajax()) {
            return view('PkgUtilisateurs::ville._fields', compact('itemVille'));
        }

        return view('PkgUtilisateurs::ville.edit', compact('itemVille'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(VilleRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $ville = $this->villeService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgUtilisateurs::ville.singular')])
            ]);
        }

        return redirect()->route('villes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgUtilisateurs::ville.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $ville = $this->villeService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgUtilisateurs::ville.singular')])
            ]);
        }

        return redirect()->route('villes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgUtilisateurs::ville.singular')
                ])
        );
    }

    public function export()
    {
        $villes_data = $this->villeService->all();
        return Excel::download(new VilleExport($villes_data), 'ville_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new VilleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('villes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('villes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgUtilisateurs::ville.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getVilles()
    {
        $villes = $this->villeService->all();
        return response()->json($villes);
    }
}
