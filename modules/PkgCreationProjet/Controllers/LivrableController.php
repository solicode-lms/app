<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCreationProjet\App\Requests\LivrableRequest;
use Modules\PkgCreationProjet\Services\LivrableService;
use Modules\PkgCreationProjet\Services\NatureLivrableService;
use Modules\PkgCreationProjet\Services\ProjetService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\LivrableExport;
use Modules\PkgCreationProjet\App\Imports\LivrableImport;

class LivrableController extends AdminController
{
    protected $livrableService;
    protected $natureLivrableService;
    protected $projetService;

    public function __construct(LivrableService $livrableService, NatureLivrableService $natureLivrableService, ProjetService $projetService)
    {
        parent::__construct();
        $this->livrableService = $livrableService;
        $this->natureLivrableService = $natureLivrableService;
        $this->projetService = $projetService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $livrable_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $livrables_data = $this->livrableService->paginate($livrable_searchQuery);

        if ($request->ajax()) {
            return view('PkgCreationProjet::livrable._table', compact('livrables_data'))->render();
        }

        return view('PkgCreationProjet::livrable.index', compact('livrables_data','livrable_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemLivrable = $this->livrableService->createInstance();
        $natureLivrables = $this->natureLivrableService->all();
        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::livrable._fields', compact('itemLivrable', 'natureLivrables', 'projets'));
        }
        return view('PkgCreationProjet::livrable.create', compact('itemLivrable', 'natureLivrables', 'projets'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(LivrableRequest $request)
    {
        $validatedData = $request->validated();
        $livrable = $this->livrableService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $livrable,
                'modelName' => __('PkgCreationProjet::livrable.singular')])
            ]);
        }

        return redirect()->route('livrables.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $livrable,
                'modelName' => __('PkgCreationProjet::livrable.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemLivrable = $this->livrableService->find($id);
        $natureLivrables = $this->natureLivrableService->all();
        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::livrable._fields', compact('itemLivrable', 'natureLivrables', 'projets'));
        }

        return view('PkgCreationProjet::livrable.show', compact('itemLivrable'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemLivrable = $this->livrableService->find($id);
        $natureLivrables = $this->natureLivrableService->all();
        $projets = $this->projetService->all();

        if (request()->ajax()) {
            return view('PkgCreationProjet::livrable._fields', compact('itemLivrable', 'natureLivrables', 'projets'));
        }

        return view('PkgCreationProjet::livrable.edit', compact('itemLivrable', 'natureLivrables', 'projets'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(LivrableRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $livrable = $this->livrableService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')])
            ]);
        }

        return redirect()->route('livrables.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $livrable = $this->livrableService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')])
            ]);
        }

        return redirect()->route('livrables.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $livrable,
                'modelName' =>  __('PkgCreationProjet::livrable.singular')
                ])
        );
    }

    public function export()
    {
        $livrables_data = $this->livrableService->all();
        return Excel::download(new LivrableExport($livrables_data), 'livrable_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new LivrableImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('livrables.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('livrables.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::livrable.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getLivrables()
    {
        $livrables = $this->livrableService->all();
        return response()->json($livrables);
    }
}
