<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\NiveauDifficulteService;
use Modules\PkgFormation\Services\FormateurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\NiveauDifficulteRequest;
use Modules\PkgCompetences\Models\NiveauDifficulte;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\NiveauDifficulteExport;
use Modules\PkgCompetences\App\Imports\NiveauDifficulteImport;
use Modules\Core\Services\ContextState;

class BaseNiveauDifficulteController extends AdminController
{
    protected $niveauDifficulteService;
    protected $formateurService;

    public function __construct(NiveauDifficulteService $niveauDifficulteService, FormateurService $formateurService) {
        parent::__construct();
        $this->niveauDifficulteService = $niveauDifficulteService;
        $this->formateurService = $formateurService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('niveauDifficulte.index');
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.niveauDifficulte.formateur_id') == null){
           $this->viewState->init('filter.niveauDifficulte.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        // Extraire les paramètres de recherche, page, et filtres
        $niveauDifficultes_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('niveauDifficultes_search', $this->viewState->get("filter.niveauDifficulte.niveauDifficultes_search"))],
            $request->except(['niveauDifficultes_search', 'page', 'sort'])
        );

        // Paginer les niveauDifficultes
        $niveauDifficultes_data = $this->niveauDifficulteService->paginate($niveauDifficultes_params);

        // Récupérer les statistiques et les champs filtrables
        $niveauDifficultes_stats = $this->niveauDifficulteService->getniveauDifficulteStats();
        $this->viewState->set('stats.niveauDifficulte.stats'  , $niveauDifficultes_stats);
        $niveauDifficultes_filters = $this->niveauDifficulteService->getFieldsFilterable();
        $niveauDifficulte_instance =  $this->niveauDifficulteService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgCompetences::niveauDifficulte._table', compact('niveauDifficultes_data', 'niveauDifficultes_stats', 'niveauDifficultes_filters','niveauDifficulte_instance'))->render();
        }

        return view('PkgCompetences::niveauDifficulte.index', compact('niveauDifficultes_data', 'niveauDifficultes_stats', 'niveauDifficultes_filters','niveauDifficulte_instance'));
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.niveauDifficulte.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemNiveauDifficulte = $this->niveauDifficulteService->createInstance();
        

        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::niveauDifficulte._fields', compact('itemNiveauDifficulte', 'formateurs'));
        }
        return view('PkgCompetences::niveauDifficulte.create', compact('itemNiveauDifficulte', 'formateurs'));
    }
    public function store(NiveauDifficulteRequest $request) {
        $validatedData = $request->validated();
        $niveauDifficulte = $this->niveauDifficulteService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $niveauDifficulte,
                'modelName' => __('PkgCompetences::niveauDifficulte.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $niveauDifficulte->id]
            );
        }

        return redirect()->route('niveauDifficultes.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $niveauDifficulte,
                'modelName' => __('PkgCompetences::niveauDifficulte.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('niveauDifficulte.edit_' . $id);


        $itemNiveauDifficulte = $this->niveauDifficulteService->find($id);
        $this->authorize('view', $itemNiveauDifficulte);


        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::niveauDifficulte._fields', compact('itemNiveauDifficulte', 'formateurs'));
        }

        return view('PkgCompetences::niveauDifficulte.edit', compact('itemNiveauDifficulte', 'formateurs'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('niveauDifficulte.edit_' . $id);


        $itemNiveauDifficulte = $this->niveauDifficulteService->find($id);
        $this->authorize('edit', $itemNiveauDifficulte);


        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::niveauDifficulte._fields', compact('itemNiveauDifficulte', 'formateurs'));
        }

        return view('PkgCompetences::niveauDifficulte.edit', compact('itemNiveauDifficulte', 'formateurs'));

    }
    public function update(NiveauDifficulteRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $niveauDifficulte = $this->niveauDifficulteService->find($id);
        $this->authorize('update', $niveauDifficulte);

        $validatedData = $request->validated();
        $niveauDifficulte = $this->niveauDifficulteService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $niveauDifficulte,
                'modelName' =>  __('PkgCompetences::niveauDifficulte.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $niveauDifficulte->id]
            );
        }

        return redirect()->route('niveauDifficultes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $niveauDifficulte,
                'modelName' =>  __('PkgCompetences::niveauDifficulte.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $niveauDifficulte = $this->niveauDifficulteService->find($id);
        $this->authorize('delete', $niveauDifficulte);

        $niveauDifficulte = $this->niveauDifficulteService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauDifficulte,
                'modelName' =>  __('PkgCompetences::niveauDifficulte.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('niveauDifficultes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauDifficulte,
                'modelName' =>  __('PkgCompetences::niveauDifficulte.singular')
                ])
        );

    }

    public function export($format)
    {
        $niveauDifficultes_data = $this->niveauDifficulteService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new NiveauDifficulteExport($niveauDifficultes_data,'csv'), 'niveauDifficulte_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new NiveauDifficulteExport($niveauDifficultes_data,'xlsx'), 'niveauDifficulte_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else {
            return response()->json(['error' => 'Format non supporté'], 400);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new NiveauDifficulteImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('niveauDifficultes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('niveauDifficultes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::niveauDifficulte.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNiveauDifficultes()
    {
        $niveauDifficultes = $this->niveauDifficulteService->all();
        return response()->json($niveauDifficultes);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $niveauDifficulte = $this->niveauDifficulteService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedNiveauDifficulte = $this->niveauDifficulteService->dataCalcul($niveauDifficulte);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedNiveauDifficulte
        ]);
    }
    

}
