<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\SpecialiteRequest;
use Modules\PkgUtilisateurs\Services\SpecialiteService;
use Modules\PkgUtilisateurs\Services\FormateurService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\SpecialiteExport;
use Modules\PkgUtilisateurs\App\Imports\SpecialiteImport;

class SpecialiteController extends AdminController
{
    protected $specialiteService;
    protected $formateurService;

    public function __construct(SpecialiteService $specialiteService, FormateurService $formateurService)
    {
        $this->specialiteService = $specialiteService;
        $this->formateurService = $formateurService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->specialiteService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgUtilisateurs::specialite.table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgUtilisateurs::specialite.index', compact('data'));
    }

    public function create()
    {
        $item = $this->specialiteService->createInstance();
        $formateurs = $this->formateurService->all();
        return view('PkgUtilisateurs::specialite.create', compact('item', 'formateurs'));
    }

    public function store(SpecialiteRequest $request)
    {
        $validatedData = $request->validated();
        $specialite = $this->specialiteService->create($validatedData);

        if ($request->has('formateurs')) {
            $specialite->formateurs()->sync($request->input('formateurs'));
        }

        return redirect()->route('specialites.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $specialite,
            'modelName' => __('PkgUtilisateurs::specialite.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->specialiteService->find($id);
        return view('PkgUtilisateurs::specialite.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->specialiteService->find($id);
        $formateurs = $this->formateurService->all();
        return view('PkgUtilisateurs::specialite.edit', compact('item', 'formateurs'));
    }

    public function update(SpecialiteRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $specialite = $this->specialiteService->update($id, $validatedData);


        if ($request->has('formateurs')) {
            $specialite->formateurs()->sync($request->input('formateurs'));
        }

        return redirect()->route('specialites.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgUtilisateurs::specialite.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $specialite = $this->specialiteService->destroy($id);
        return redirect()->route('specialites.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgUtilisateurs::specialite.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->specialiteService->all();
        return Excel::download(new SpecialiteExport($data), 'specialite_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new SpecialiteImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('specialites.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('specialites.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgUtilisateurs::specialite.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSpecialites()
    {
        $specialites = $this->specialiteService->all();
        return response()->json($specialites);
    }
}
