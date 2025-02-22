<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Modules\PkgCompetences\Services\TechnologyService;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgCompetences\Services\NiveauDifficulteService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationProjet\App\Requests\TransfertCompetenceRequest;
use Modules\PkgCreationProjet\Models\TransfertCompetence;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\TransfertCompetenceExport;
use Modules\PkgCreationProjet\App\Imports\TransfertCompetenceImport;
use Modules\Core\Services\ContextState;

class BaseTransfertCompetenceController extends AdminController
{
    protected $transfertCompetenceService;
    protected $technologyService;
    protected $competenceService;
    protected $niveauDifficulteService;
    protected $projetService;

    public function __construct(TransfertCompetenceService $transfertCompetenceService, TechnologyService $technologyService, CompetenceService $competenceService, NiveauDifficulteService $niveauDifficulteService, ProjetService $projetService) {
        parent::__construct();
        $this->transfertCompetenceService = $transfertCompetenceService;
        $this->technologyService = $technologyService;
        $this->competenceService = $competenceService;
        $this->niveauDifficulteService = $niveauDifficulteService;
        $this->projetService = $projetService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('transfertCompetence.index');
        if($this->sessionState->get('formateur_id')) $this->viewState->init('scope.transfertCompetence.formateur_id'  , $this->sessionState->get('formateur_id'));

        // Extraire les paramètres de recherche, page, et filtres
        $transfertCompetences_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('transfertCompetences_search', $this->viewState->get("filter.transfertCompetence.transfertCompetences_search"))],
            $request->except(['transfertCompetences_search', 'page', 'sort'])
        );

        // Paginer les transfertCompetences
        $transfertCompetences_data = $this->transfertCompetenceService->paginate($transfertCompetences_params);

        // Récupérer les statistiques et les champs filtrables
        $transfertCompetences_stats = $this->transfertCompetenceService->gettransfertCompetenceStats();
        $transfertCompetences_filters = $this->transfertCompetenceService->getFieldsFilterable();
        $transfertCompetence_instance =  $this->transfertCompetenceService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgCreationProjet::transfertCompetence._table', compact('transfertCompetences_data', 'transfertCompetences_stats', 'transfertCompetences_filters','transfertCompetence_instance'))->render();
        }

        return view('PkgCreationProjet::transfertCompetence.index', compact('transfertCompetences_data', 'transfertCompetences_stats', 'transfertCompetences_filters','transfertCompetence_instance'));
    }
    public function create() {
        $this->viewState->set('scope_form.transfertCompetence.formateur_id'  , $this->sessionState->get('formateur_id'));
        $itemTransfertCompetence = $this->transfertCompetenceService->createInstance();
        
        $value = $itemTransfertCompetence->getNestedValue('projet.filiere_id');
        $key = 'scope.competence.module.filiere_id';
        $this->viewState->set($key, $value);
        $competences = $this->competenceService->all();
        $value = $itemTransfertCompetence->getNestedValue('projet.formateur_id');
        $key = 'scope.niveauDifficulte.formateur_id';
        $this->viewState->set($key, $value);
        $niveauDifficultes = $this->niveauDifficulteService->all();
        $technologies = $this->technologyService->all();
        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::transfertCompetence._fields', compact('itemTransfertCompetence', 'technologies', 'competences', 'niveauDifficultes', 'projets'));
        }
        return view('PkgCreationProjet::transfertCompetence.create', compact('itemTransfertCompetence', 'technologies', 'competences', 'niveauDifficultes', 'projets'));
    }
    public function store(TransfertCompetenceRequest $request) {
        $validatedData = $request->validated();
        $transfertCompetence = $this->transfertCompetenceService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $transfertCompetence,
                'modelName' => __('PkgCreationProjet::transfertCompetence.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $transfertCompetence->id]
            );
        }

        return redirect()->route('transfertCompetences.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $transfertCompetence,
                'modelName' => __('PkgCreationProjet::transfertCompetence.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('transfertCompetence.edit_' . $id);
     
        $itemTransfertCompetence = $this->transfertCompetenceService->find($id);
  
        $technologies = $this->technologyService->all();
        $competences = $this->competenceService->all();
        $niveauDifficultes = $this->niveauDifficulteService->all();
        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::transfertCompetence._fields', compact('itemTransfertCompetence', 'technologies', 'competences', 'niveauDifficultes', 'projets'));
        }

        return view('PkgCreationProjet::transfertCompetence.edit', compact('itemTransfertCompetence', 'technologies', 'competences', 'niveauDifficultes', 'projets'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('transfertCompetence.edit_' . $id);

        $itemTransfertCompetence = $this->transfertCompetenceService->find($id);
        $this->authorize('edit', $itemTransfertCompetence);

        $value = $itemTransfertCompetence->getNestedValue('projet.filiere_id');
        $key = 'scope.competence.module.filiere_id';
        $this->viewState->set($key, $value);
        $competences = $this->competenceService->all();
        $value = $itemTransfertCompetence->getNestedValue('projet.formateur_id');
        $key = 'scope.niveauDifficulte.formateur_id';
        $this->viewState->set($key, $value);
        $niveauDifficultes = $this->niveauDifficulteService->all();
        $technologies = $this->technologyService->all();
        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::transfertCompetence._fields', compact('itemTransfertCompetence', 'technologies', 'competences', 'niveauDifficultes', 'projets'));
        }

        return view('PkgCreationProjet::transfertCompetence.edit', compact('itemTransfertCompetence', 'technologies', 'competences', 'niveauDifficultes', 'projets'));

    }
    public function update(TransfertCompetenceRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $transfertCompetence = $this->transfertCompetenceService->find($id);
        $this->authorize('update', $transfertCompetence);

        $validatedData = $request->validated();
        $transfertCompetence = $this->transfertCompetenceService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $transfertCompetence,
                'modelName' =>  __('PkgCreationProjet::transfertCompetence.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $transfertCompetence->id]
            );
        }

        return redirect()->route('transfertCompetences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $transfertCompetence,
                'modelName' =>  __('PkgCreationProjet::transfertCompetence.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $transfertCompetence = $this->transfertCompetenceService->find($id);
        $this->authorize('delete', $transfertCompetence);

        $transfertCompetence = $this->transfertCompetenceService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $transfertCompetence,
                'modelName' =>  __('PkgCreationProjet::transfertCompetence.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('transfertCompetences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $transfertCompetence,
                'modelName' =>  __('PkgCreationProjet::transfertCompetence.singular')
                ])
        );

    }

    public function export($format)
    {
        $transfertCompetences_data = $this->transfertCompetenceService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new TransfertCompetenceExport($transfertCompetences_data,'csv'), 'transfertCompetence_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new TransfertCompetenceExport($transfertCompetences_data,'xlsx'), 'transfertCompetence_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new TransfertCompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('transfertCompetences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('transfertCompetences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::transfertCompetence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getTransfertCompetences()
    {
        $transfertCompetences = $this->transfertCompetenceService->all();
        return response()->json($transfertCompetences);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $transfertCompetence = $this->transfertCompetenceService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedTransfertCompetence = $this->transfertCompetenceService->dataCalcul($transfertCompetence);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedTransfertCompetence
        ]);
    }
    

}
