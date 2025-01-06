<?php


namespace Modules\PkgCompetences\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCompetences\App\Requests\AppreciationRequest;
use Modules\PkgCompetences\Services\AppreciationService;
use Modules\PkgUtilisateurs\Services\FormateurService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\AppreciationExport;
use Modules\PkgCompetences\App\Imports\AppreciationImport;
use Modules\Core\Services\ContextState;
use Modules\PkgCompetences\Models\Appreciation;

class BaseAppreciationController extends AdminController
{
    protected $appreciationService;
    protected $formateurService;

    public function __construct(AppreciationService $appreciationService, FormateurService $formateurService)
    {
        parent::__construct();
        $this->appreciationService = $appreciationService;
        $this->formateurService = $formateurService;

     

    }


    public function index(Request $request)
    {
 
        // Extraire les paramètres de recherche, page, et filtres
        $appreciations_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('appreciations_search', '')],
            $request->except(['appreciations_search', 'page', 'sort'])
        );
    
        // Paginer les appreciations
        $appreciations_data = $this->appreciationService->paginate($appreciations_params);
    
        // Récupérer les statistiques et les champs filtrables
        $appreciations_stats = $this->appreciationService->getappreciationStats();
        $appreciations_filters = $this->appreciationService->getFieldsFilterable();
    
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgCompetences::appreciation._table', compact('appreciations_data', 'appreciations_stats', 'appreciations_filters'))->render();
        }
    
        return view('PkgCompetences::appreciation.index', compact('appreciations_data', 'appreciations_stats', 'appreciations_filters'));
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
