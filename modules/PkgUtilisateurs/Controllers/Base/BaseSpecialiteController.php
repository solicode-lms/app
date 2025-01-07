<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\SpecialiteRequest;
use Modules\PkgUtilisateurs\Services\SpecialiteService;
use Modules\PkgUtilisateurs\Services\FormateurService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\SpecialiteExport;
use Modules\PkgUtilisateurs\App\Imports\SpecialiteImport;
use Modules\Core\Services\ContextState;

class BaseSpecialiteController extends AdminController
{
    protected $specialiteService;
    protected $formateurService;

    public function __construct(SpecialiteService $specialiteService, FormateurService $formateurService)
    {
        parent::__construct();
        $this->specialiteService = $specialiteService;
        $this->formateurService = $formateurService;

    }


    public function index(Request $request)
    {
        // Extraire les paramètres de recherche, page, et filtres
        $specialites_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('specialites_search', '')],
            $request->except(['specialites_search', 'page', 'sort'])
        );
    
        // Paginer les specialites
        $specialites_data = $this->specialiteService->paginate($specialites_params);
    
        // Récupérer les statistiques et les champs filtrables
        $specialites_stats = $this->specialiteService->getspecialiteStats();
        $specialites_filters = $this->specialiteService->getFieldsFilterable();
    
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgUtilisateurs::specialite._table', compact('specialites_data', 'specialites_stats', 'specialites_filters'))->render();
        }
    
        return view('PkgUtilisateurs::specialite.index', compact('specialites_data', 'specialites_stats', 'specialites_filters'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemSpecialite = $this->specialiteService->createInstance();
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgUtilisateurs::specialite._fields', compact('itemSpecialite', 'formateurs'));
        }
        return view('PkgUtilisateurs::specialite.create', compact('itemSpecialite', 'formateurs'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(SpecialiteRequest $request)
    {
        $validatedData = $request->validated();
        $specialite = $this->specialiteService->create($validatedData);


        if ($request->has('formateurs')) {
            $specialite->formateurs()->sync($request->input('formateurs'));
        }


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $specialite,
                'modelName' => __('PkgUtilisateurs::specialite.singular')])
            ]);
        }

        return redirect()->route('specialites.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $specialite,
                'modelName' => __('PkgUtilisateurs::specialite.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemSpecialite = $this->specialiteService->find($id);
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgUtilisateurs::specialite._fields', compact('itemSpecialite', 'formateurs'));
        }

        return view('PkgUtilisateurs::specialite.show', compact('itemSpecialite'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {

        $itemSpecialite = $this->specialiteService->find($id);
        $formateurs = $this->formateurService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('specialite_id', $id);


        if (request()->ajax()) {
            return view('PkgUtilisateurs::specialite._fields', compact('itemSpecialite', 'formateurs'));
        }

        return view('PkgUtilisateurs::specialite.edit', compact('itemSpecialite', 'formateurs'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(SpecialiteRequest $request, string $id)
    {

        $validatedData = $request->validated();
        $specialite = $this->specialiteService->update($id, $validatedData);

        $specialite->formateurs()->sync($request->input('formateurs'));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgUtilisateurs::specialite.singular')])
            ]);
        }

        return redirect()->route('specialites.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgUtilisateurs::specialite.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {

        $specialite = $this->specialiteService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgUtilisateurs::specialite.singular')])
            ]);
        }

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
        $specialites_data = $this->specialiteService->all();
        return Excel::download(new SpecialiteExport($specialites_data), 'specialite_export.xlsx');
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
