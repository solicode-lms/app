<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\FormateurRequest;
use Modules\PkgUtilisateurs\Services\FormateurService;
use Modules\PkgUtilisateurs\Services\GroupeService;
use Modules\PkgUtilisateurs\Services\SpecialiteService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\FormateurExport;
use Modules\PkgUtilisateurs\App\Imports\FormateurImport;

class FormateurController extends AdminController
{
    protected $formateurService;
    protected $groupeService;
    protected $specialiteService;

    public function __construct(FormateurService $formateurService, GroupeService $groupeService, SpecialiteService $specialiteService)
    {
        $this->formateurService = $formateurService;
        $this->groupeService = $groupeService;
        $this->specialiteService = $specialiteService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->formateurService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgUtilisateurs::_formateur.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgUtilisateurs::formateur.index', compact('data'));
    }

    public function create()
    {
        $item = $this->formateurService->createInstance();
        $groupes = $this->groupeService->all();
        $specialites = $this->specialiteService->all();
        return view('PkgUtilisateurs::formateur.create', compact('item', 'groupes', 'specialites'));
    }

    public function store(FormateurRequest $request)
    {
        $validatedData = $request->validated();
        $formateur = $this->formateurService->create($validatedData);

        if ($request->has('groupes')) {
            $formateur->groupes()->sync($request->input('groupes'));
        }
        if ($request->has('specialites')) {
            $formateur->specialites()->sync($request->input('specialites'));
        }

        return redirect()->route('formateurs.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $formateur,
            'modelName' => __('PkgUtilisateurs::formateur.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->formateurService->find($id);
        return view('PkgUtilisateurs::formateur.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->formateurService->find($id);
        $groupes = $this->groupeService->all();
        $specialites = $this->specialiteService->all();
        return view('PkgUtilisateurs::formateur.edit', compact('item', 'groupes', 'specialites'));
    }

    public function update(FormateurRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $formateur = $this->formateurService->update($id, $validatedData);


        if ($request->has('groupes')) {
            $formateur->groupes()->sync($request->input('groupes'));
        }
        if ($request->has('specialites')) {
            $formateur->specialites()->sync($request->input('specialites'));
        }

        return redirect()->route('formateurs.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgUtilisateurs::formateur.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $formateur = $this->formateurService->destroy($id);
        return redirect()->route('formateurs.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgUtilisateurs::formateur.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->formateurService->all();
        return Excel::download(new FormateurExport($data), 'formateur_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new FormateurImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('formateurs.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('formateurs.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgUtilisateurs::formateur.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFormateurs()
    {
        $formateurs = $this->formateurService->all();
        return response()->json($formateurs);
    }
}
