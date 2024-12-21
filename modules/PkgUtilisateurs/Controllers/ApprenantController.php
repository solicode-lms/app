<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\ApprenantRequest;
use Modules\PkgUtilisateurs\Services\ApprenantService;
use Modules\PkgUtilisateurs\Services\GroupeService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\ApprenantExport;
use Modules\PkgUtilisateurs\App\Imports\ApprenantImport;

class ApprenantController extends AdminController
{
    protected $apprenantService;
    protected $groupeService;

    public function __construct(ApprenantService $apprenantService, GroupeService $groupeService)
    {
        $this->apprenantService = $apprenantService;
        $this->groupeService = $groupeService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->apprenantService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgUtilisateurs::apprenant._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgUtilisateurs::apprenant.index', compact('data'));
    }

    public function create()
    {
        $item = $this->apprenantService->createInstance();
        $groupes = $this->groupeService->all();
        return view('PkgUtilisateurs::apprenant.create', compact('item', 'groupes'));
    }

    public function store(ApprenantRequest $request)
    {
        $validatedData = $request->validated();
        $apprenant = $this->apprenantService->create($validatedData);

        if ($request->has('groupes')) {
            $apprenant->groupes()->sync($request->input('groupes'));
        }

        return redirect()->route('apprenants.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $apprenant,
            'modelName' => __('PkgUtilisateurs::apprenant.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->apprenantService->find($id);
        return view('PkgUtilisateurs::apprenant.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->apprenantService->find($id);
        $groupes = $this->groupeService->all();
        return view('PkgUtilisateurs::apprenant.edit', compact('item', 'groupes'));
    }

    public function update(ApprenantRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $apprenant = $this->apprenantService->update($id, $validatedData);


        if ($request->has('groupes')) {
            $apprenant->groupes()->sync($request->input('groupes'));
        }

        return redirect()->route('apprenants.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $apprenant,
                'modelName' =>  __('PkgUtilisateurs::apprenant.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $apprenant = $this->apprenantService->destroy($id);
        return redirect()->route('apprenants.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenant,
                'modelName' =>  __('PkgUtilisateurs::apprenant.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->apprenantService->all();
        return Excel::download(new ApprenantExport($data), 'apprenant_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ApprenantImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('apprenants.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('apprenants.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgUtilisateurs::apprenant.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getApprenants()
    {
        $apprenants = $this->apprenantService->all();
        return response()->json($apprenants);
    }
}
