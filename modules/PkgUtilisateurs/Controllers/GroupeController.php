<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\GroupeRequest;
use Modules\PkgUtilisateurs\Services\GroupeService;
use Modules\PkgUtilisateurs\Services\ApprenantService;
use Modules\PkgUtilisateurs\Services\FormateurService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\GroupeExport;
use Modules\PkgUtilisateurs\App\Imports\GroupeImport;

class GroupeController extends AdminController
{
    protected $groupeService;
    protected $apprenantService;
    protected $formateurService;

    public function __construct(GroupeService $groupeService, ApprenantService $apprenantService, FormateurService $formateurService)
    {
        $this->groupeService = $groupeService;
        $this->apprenantService = $apprenantService;
        $this->formateurService = $formateurService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->groupeService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgUtilisateurs::_groupe.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgUtilisateurs::groupe.index', compact('data'));
    }

    public function create()
    {
        $item = $this->groupeService->createInstance();
        $apprenants = $this->apprenantService->all();
        $formateurs = $this->formateurService->all();
        return view('PkgUtilisateurs::groupe.create', compact('item', 'apprenants', 'formateurs'));
    }

    public function store(GroupeRequest $request)
    {
        $validatedData = $request->validated();
        $groupe = $this->groupeService->create($validatedData);

        if ($request->has('apprenants')) {
            $groupe->apprenants()->sync($request->input('apprenants'));
        }
        if ($request->has('formateurs')) {
            $groupe->formateurs()->sync($request->input('formateurs'));
        }

        return redirect()->route('groupes.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $groupe,
            'modelName' => __('PkgUtilisateurs::groupe.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->groupeService->find($id);
        return view('PkgUtilisateurs::groupe.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->groupeService->find($id);
        $apprenants = $this->apprenantService->all();
        $formateurs = $this->formateurService->all();
        return view('PkgUtilisateurs::groupe.edit', compact('item', 'apprenants', 'formateurs'));
    }

    public function update(GroupeRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $groupe = $this->groupeService->update($id, $validatedData);


        if ($request->has('apprenants')) {
            $groupe->apprenants()->sync($request->input('apprenants'));
        }
        if ($request->has('formateurs')) {
            $groupe->formateurs()->sync($request->input('formateurs'));
        }

        return redirect()->route('groupes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgUtilisateurs::groupe.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $groupe = $this->groupeService->destroy($id);
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
