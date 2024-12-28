<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\ApprenantKonosyRequest;
use Modules\PkgUtilisateurs\Services\ApprenantKonosyService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\ApprenantKonosyExport;
use Modules\PkgUtilisateurs\App\Imports\ApprenantKonosyImport;

class ApprenantKonosyController extends AdminController
{
    protected $apprenantKonosyService;

    public function __construct(ApprenantKonosyService $apprenantKonosyService)
    {
        parent::__construct();
        $this->apprenantKonosyService = $apprenantKonosyService;
    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $data = $this->apprenantKonosyService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('PkgUtilisateurs::apprenantKonosy._table', compact('data'))->render();
        }

        return view('PkgUtilisateurs::apprenantKonosy.index', compact('data','searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemApprenantKonosy = $this->apprenantKonosyService->createInstance();

        if (request()->ajax()) {
            return view('PkgUtilisateurs::apprenantKonosy._fields', compact('itemApprenantKonosy'));
        }
        return view('PkgUtilisateurs::apprenantKonosy.create', compact('itemApprenantKonosy'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(ApprenantKonosyRequest $request)
    {
        $validatedData = $request->validated();
        $apprenantKonosy = $this->apprenantKonosyService->create($validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' => __('PkgUtilisateurs::apprenantKonosy.singular')])
            ]);
        }

        return redirect()->route('apprenantKonosys.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $apprenantKonosy,
                'modelName' => __('PkgUtilisateurs::apprenantKonosy.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemApprenantKonosy = $this->apprenantKonosyService->find($id);

        if (request()->ajax()) {
            return view('PkgUtilisateurs::apprenantKonosy._fields', compact('itemApprenantKonosy'));
        }

        return view('PkgUtilisateurs::apprenantkonosy.show', compact('itemApprenantKonosy'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemApprenantKonosy = $this->apprenantKonosyService->find($id);

        if (request()->ajax()) {
            return view('PkgUtilisateurs::apprenantKonosy._fields', compact('itemApprenantKonosy'));
        }

        return view('PkgUtilisateurs::apprenantKonosy.edit', compact('itemApprenantKonosy'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(ApprenantKonosyRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $apprenantkonosy = $this->apprenantKonosyService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $apprenantkonosy,
                'modelName' =>  __('PkgUtilisateurs::apprenantkonosy.singular')])
            ]);
        }

        return redirect()->route('apprenantKonosies.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $apprenantkonosy,
                'modelName' =>  __('PkgUtilisateurs::apprenantkonosy.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $apprenantkonosy = $this->apprenantKonosyService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenantkonosy,
                'modelName' =>  __('PkgUtilisateurs::apprenantkonosy.singular')])
            ]);
        }

        return redirect()->route('apprenantKonosies.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenantkonosy,
                'modelName' =>  __('PkgUtilisateurs::apprenantkonosy.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->apprenantKonosyService->all();
        return Excel::download(new ApprenantKonosyExport($data), 'apprenantKonosy_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ApprenantKonosyImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('apprenantKonosies.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('apprenantKonosies.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgUtilisateurs::apprenantkonosy.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getApprenantKonosies()
    {
        $apprenantKonosies = $this->apprenantKonosyService->all();
        return response()->json($apprenantKonosies);
    }
}
