<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\NationaliteRequest;
use Modules\PkgUtilisateurs\Services\NationaliteService;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\NationaliteExport;
use Modules\PkgUtilisateurs\App\Imports\NationaliteImport;
use Modules\Core\Services\ContextState;

class BaseNationaliteController extends AdminController
{
    protected $nationaliteService;

    public function __construct(NationaliteService $nationaliteService)
    {
        parent::__construct();
        $this->nationaliteService = $nationaliteService;

    }


    public function index(Request $request)
    {
        // Extraire les paramètres de recherche, page, et filtres
        $nationalites_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('nationalites_search', '')],
            $request->except(['nationalites_search', 'page', 'sort'])
        );
    
        // Paginer les nationalites
        $nationalites_data = $this->nationaliteService->paginate($nationalites_params);
    
        // Récupérer les statistiques et les champs filtrables
        $nationalites_stats = $this->nationaliteService->getnationaliteStats();
        $nationalites_filters = $this->nationaliteService->getFieldsFilterable();
    
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgUtilisateurs::nationalite._table', compact('nationalites_data', 'nationalites_stats', 'nationalites_filters'))->render();
        }
    
        return view('PkgUtilisateurs::nationalite.index', compact('nationalites_data', 'nationalites_stats', 'nationalites_filters'));
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

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('nationalite_id', $id);


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
        $nationalites_data = $this->nationaliteService->all();
        return Excel::download(new NationaliteExport($nationalites_data), 'nationalite_export.xlsx');
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
