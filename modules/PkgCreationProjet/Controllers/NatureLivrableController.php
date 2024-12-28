<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCreationProjet\App\Requests\NatureLivrableRequest;
use Modules\PkgCreationProjet\Services\NatureLivrableService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\NatureLivrableExport;
use Modules\PkgCreationProjet\App\Imports\NatureLivrableImport;

class NatureLivrableController extends AdminController
{
    protected $natureLivrableService;

    public function __construct(NatureLivrableService $natureLivrableService)
    {
        parent::__construct();
        $this->natureLivrableService = $natureLivrableService;
    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $data = $this->natureLivrableService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('PkgCreationProjet::natureLivrable._table', compact('data'))->render();
        }

        return view('PkgCreationProjet::natureLivrable.index', compact('data','searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemNatureLivrable = $this->natureLivrableService->createInstance();

        if (request()->ajax()) {
            return view('PkgCreationProjet::natureLivrable._fields', compact('itemNatureLivrable'));
        }
        return view('PkgCreationProjet::natureLivrable.create', compact('itemNatureLivrable'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(NatureLivrableRequest $request)
    {
        $validatedData = $request->validated();
        $natureLivrable = $this->natureLivrableService->create($validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' => __('PkgCreationProjet::natureLivrable.singular')])
            ]);
        }

        return redirect()->route('natureLivrables.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' => __('PkgCreationProjet::natureLivrable.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemNatureLivrable = $this->natureLivrableService->find($id);

        if (request()->ajax()) {
            return view('PkgCreationProjet::natureLivrable._fields', compact('itemNatureLivrable'));
        }

        return view('PkgCreationProjet::naturelivrable.show', compact('itemNatureLivrable'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemNatureLivrable = $this->natureLivrableService->find($id);

        if (request()->ajax()) {
            return view('PkgCreationProjet::natureLivrable._fields', compact('itemNatureLivrable'));
        }

        return view('PkgCreationProjet::natureLivrable.edit', compact('itemNatureLivrable'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(NatureLivrableRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $naturelivrable = $this->natureLivrableService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $naturelivrable,
                'modelName' =>  __('PkgCreationProjet::naturelivrable.singular')])
            ]);
        }

        return redirect()->route('natureLivrables.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $naturelivrable,
                'modelName' =>  __('PkgCreationProjet::naturelivrable.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $naturelivrable = $this->natureLivrableService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $naturelivrable,
                'modelName' =>  __('PkgCreationProjet::naturelivrable.singular')])
            ]);
        }

        return redirect()->route('natureLivrables.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $naturelivrable,
                'modelName' =>  __('PkgCreationProjet::naturelivrable.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->natureLivrableService->all();
        return Excel::download(new NatureLivrableExport($data), 'natureLivrable_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new NatureLivrableImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('natureLivrables.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('natureLivrables.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::naturelivrable.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNatureLivrables()
    {
        $natureLivrables = $this->natureLivrableService->all();
        return response()->json($natureLivrables);
    }
}
