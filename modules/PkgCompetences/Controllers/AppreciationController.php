<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\AppreciationRequest;
use Modules\PkgCompetences\Services\AppreciationService;
use Modules\PkgUtilisateurs\Services\FormateurService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\AppreciationExport;
use Modules\PkgCompetences\App\Imports\AppreciationImport;
use Modules\Core\Services\ContextState;

class AppreciationController extends AdminController
{
    protected $appreciationService;
    protected $formateurService;

    public function __construct(AppreciationService $appreciationService, FormateurService $formateurService)
    {
        parent::__construct();
        $this->appreciationService = $appreciationService;
        $this->formateurService = $formateurService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $appreciation_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $appreciations_data = $this->appreciationService->paginate($appreciation_searchQuery);

        if ($request->ajax()) {
            return view('PkgCompetences::appreciation._table', compact('appreciations_data'))->render();
        }

        return view('PkgCompetences::appreciation.index', compact('appreciations_data','appreciation_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemAppreciation = $this->appreciationService->createInstance();
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::appreciation._fields', compact('itemAppreciation', 'formateurs'));
        }
        return view('PkgCompetences::appreciation.create', compact('itemAppreciation', 'formateurs'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(AppreciationRequest $request)
    {
        $validatedData = $request->validated();
        $appreciation = $this->appreciationService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $appreciation,
                'modelName' => __('PkgCompetences::appreciation.singular')])
            ]);
        }

        return redirect()->route('appreciations.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $appreciation,
                'modelName' => __('PkgCompetences::appreciation.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemAppreciation = $this->appreciationService->find($id);
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::appreciation._fields', compact('itemAppreciation', 'formateurs'));
        }

        return view('PkgCompetences::appreciation.show', compact('itemAppreciation'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemAppreciation = $this->appreciationService->find($id);
        $formateurs = $this->formateurService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('appreciation_id', $id);


        if (request()->ajax()) {
            return view('PkgCompetences::appreciation._fields', compact('itemAppreciation', 'formateurs'));
        }

        return view('PkgCompetences::appreciation.edit', compact('itemAppreciation', 'formateurs'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(AppreciationRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $appreciation = $this->appreciationService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $appreciation,
                'modelName' =>  __('PkgCompetences::appreciation.singular')])
            ]);
        }

        return redirect()->route('appreciations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $appreciation,
                'modelName' =>  __('PkgCompetences::appreciation.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $appreciation = $this->appreciationService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $appreciation,
                'modelName' =>  __('PkgCompetences::appreciation.singular')])
            ]);
        }

        return redirect()->route('appreciations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $appreciation,
                'modelName' =>  __('PkgCompetences::appreciation.singular')
                ])
        );
    }

    public function export()
    {
        $appreciations_data = $this->appreciationService->all();
        return Excel::download(new AppreciationExport($appreciations_data), 'appreciation_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new AppreciationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('appreciations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('appreciations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::appreciation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getAppreciations()
    {
        $appreciations = $this->appreciationService->all();
        return response()->json($appreciations);
    }
}
