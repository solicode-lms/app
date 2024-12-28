<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\GroupeRequest;
use Modules\PkgUtilisateurs\Services\GroupeService;
use Modules\PkgUtilisateurs\Services\FormateurService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\GroupeExport;
use Modules\PkgUtilisateurs\App\Imports\GroupeImport;

class GroupeController extends AdminController
{
    protected $groupeService;
    protected $formateurService;

    public function __construct(GroupeService $groupeService, FormateurService $formateurService)
    {
        parent::__construct();
        $this->groupeService = $groupeService;
        $this->formateurService = $formateurService;
    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $data = $this->groupeService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('PkgUtilisateurs::groupe._table', compact('data'))->render();
        }

        return view('PkgUtilisateurs::groupe.index', compact('data','searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemGroupe = $this->groupeService->createInstance();
        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgUtilisateurs::groupe._fields', compact('itemGroupe', 'formateurs'));
        }
        return view('PkgUtilisateurs::groupe.create', compact('itemGroupe', 'formateurs'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(GroupeRequest $request)
    {
        $validatedData = $request->validated();
        $groupe = $this->groupeService->create($validatedData);

        if ($request->has('formateurs')) {
            $groupe->formateurs()->sync($request->input('formateurs'));
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $groupe,
                'modelName' => __('PkgUtilisateurs::groupe.singular')])
            ]);
        }

        return redirect()->route('groupes.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $groupe,
                'modelName' => __('PkgUtilisateurs::groupe.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemGroupe = $this->groupeService->find($id);
        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgUtilisateurs::groupe._fields', compact('itemGroupe', 'formateurs'));
        }

        return view('PkgUtilisateurs::groupe.show', compact('itemGroupe'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemGroupe = $this->groupeService->find($id);
        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgUtilisateurs::groupe._fields', compact('itemGroupe', 'formateurs'));
        }

        return view('PkgUtilisateurs::groupe.edit', compact('itemGroupe', 'formateurs'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(GroupeRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $groupe = $this->groupeService->update($id, $validatedData);

        $groupe->formateurs()->sync($request->input('formateurs'));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgUtilisateurs::groupe.singular')])
            ]);
        }

        return redirect()->route('groupes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgUtilisateurs::groupe.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $groupe = $this->groupeService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgUtilisateurs::groupe.singular')])
            ]);
        }

        return redirect()->route('groupes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgUtilisateurs::groupe.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->groupeService->all();
        return Excel::download(new GroupeExport($data), 'groupe_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new GroupeImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('groupes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('groupes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgUtilisateurs::groupe.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getGroupes()
    {
        $groupes = $this->groupeService->all();
        return response()->json($groupes);
    }
}
