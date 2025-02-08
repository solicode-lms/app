<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Controllers\Base;
use Modules\PkgCompetences\Services\NiveauCompetenceService;
use Modules\PkgCompetences\Services\CompetenceService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCompetences\App\Requests\NiveauCompetenceRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCompetences\App\Exports\NiveauCompetenceExport;
use Modules\PkgCompetences\App\Imports\NiveauCompetenceImport;
use Modules\Core\Services\ContextState;

class BaseNiveauCompetenceController extends AdminController
{
    protected $niveauCompetenceService;
    protected $competenceService;

    public function __construct(NiveauCompetenceService $niveauCompetenceService, CompetenceService $competenceService) {
        parent::__construct();
        $this->niveauCompetenceService = $niveauCompetenceService;
        $this->competenceService = $competenceService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $niveauCompetences_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('niveauCompetences_search', '')],
            $request->except(['niveauCompetences_search', 'page', 'sort'])
        );

        // Paginer les niveauCompetences
        $niveauCompetences_data = $this->niveauCompetenceService->paginate($niveauCompetences_params);

        // Récupérer les statistiques et les champs filtrables
        $niveauCompetences_stats = $this->niveauCompetenceService->getniveauCompetenceStats();
        $niveauCompetences_filters = $this->niveauCompetenceService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgCompetences::niveauCompetence._table', compact('niveauCompetences_data', 'niveauCompetences_stats', 'niveauCompetences_filters'))->render();
        }

        return view('PkgCompetences::niveauCompetence.index', compact('niveauCompetences_data', 'niveauCompetences_stats', 'niveauCompetences_filters'));
    }
    public function create() {
        $itemNiveauCompetence = $this->niveauCompetenceService->createInstance();
        $competences = $this->competenceService->all();


        if (request()->ajax()) {
            return view('PkgCompetences::niveauCompetence._fields', compact('itemNiveauCompetence', 'competences'));
        }
        return view('PkgCompetences::niveauCompetence.create', compact('itemNiveauCompetence', 'competences'));
    }
    public function store(NiveauCompetenceRequest $request) {
        $validatedData = $request->validated();
        $niveauCompetence = $this->niveauCompetenceService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' => __('PkgCompetences::niveauCompetence.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $niveauCompetence->id]
            );
        }

        return redirect()->route('niveauCompetences.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' => __('PkgCompetences::niveauCompetence.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('niveau_competence_id', $id);
        
        $itemNiveauCompetence = $this->niveauCompetenceService->find($id);
        $competences = $this->competenceService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::niveauCompetence._fields', compact('itemNiveauCompetence', 'competences'));
        }

        return view('PkgCompetences::niveauCompetence.edit', compact('itemNiveauCompetence', 'competences'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('niveau_competence_id', $id);
        
        $itemNiveauCompetence = $this->niveauCompetenceService->find($id);
        $competences = $this->competenceService->all();

        if (request()->ajax()) {
            return view('PkgCompetences::niveauCompetence._fields', compact('itemNiveauCompetence', 'competences'));
        }

        return view('PkgCompetences::niveauCompetence.edit', compact('itemNiveauCompetence', 'competences'));

    }
    public function update(NiveauCompetenceRequest $request, string $id) {

        $validatedData = $request->validated();
        $niveauCompetence = $this->niveauCompetenceService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' =>  __('PkgCompetences::niveauCompetence.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $niveauCompetence->id]
            );
        }

        return redirect()->route('niveauCompetences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' =>  __('PkgCompetences::niveauCompetence.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $niveauCompetence = $this->niveauCompetenceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' =>  __('PkgCompetences::niveauCompetence.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('niveauCompetences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauCompetence,
                'modelName' =>  __('PkgCompetences::niveauCompetence.singular')
                ])
        );

    }

    public function export($format)
    {
        $niveauCompetences_data = $this->niveauCompetenceService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new NiveauCompetenceExport($niveauCompetences_data,'csv'), 'niveauCompetence_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new NiveauCompetenceExport($niveauCompetences_data,'xlsx'), 'niveauCompetence_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new NiveauCompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('niveauCompetences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('niveauCompetences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCompetences::niveauCompetence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNiveauCompetences()
    {
        $niveauCompetences = $this->niveauCompetenceService->all();
        return response()->json($niveauCompetences);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $niveauCompetence = $this->niveauCompetenceService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedNiveauCompetence = $this->niveauCompetenceService->dataCalcul($niveauCompetence);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedNiveauCompetence
        ]);
    }
    


}
