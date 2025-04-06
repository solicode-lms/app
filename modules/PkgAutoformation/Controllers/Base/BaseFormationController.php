<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Controllers\Base;
use Modules\PkgAutoformation\Services\FormationService;
use Modules\PkgCompetences\Services\TechnologyService;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgAutoformation\Services\ChapitreService;
use Modules\PkgAutoformation\Services\RealisationFormationService;
use Modules\PkgFormation\Services\FiliereService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutoformation\App\Requests\FormationRequest;
use Modules\PkgAutoformation\Models\Formation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutoformation\App\Exports\FormationExport;
use Modules\PkgAutoformation\App\Imports\FormationImport;
use Modules\Core\Services\ContextState;

class BaseFormationController extends AdminController
{
    protected $formationService;
    protected $technologyService;
    protected $competenceService;
    protected $formateurService;
    protected $filiereService;

    public function __construct(FormationService $formationService, TechnologyService $technologyService, CompetenceService $competenceService, FormateurService $formateurService, FiliereService $filiereService) {
        parent::__construct();
        $this->service  =  $formationService;
        $this->formationService = $formationService;
        $this->technologyService = $technologyService;
        $this->competenceService = $competenceService;
        $this->formateurService = $formateurService;
        $this->filiereService = $filiereService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('formation.index');
        
        $viewType = $this->viewState->get('view_type', 'table');
        $viewTypes = $this->getService()->getViewTypes();
        
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.formation.formateur_id') == null){
           $this->viewState->init('filter.formation.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



        // Extraire les paramètres de recherche, page, et filtres
        $formations_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('formations_search', $this->viewState->get("filter.formation.formations_search"))],
            $request->except(['formations_search', 'page', 'sort'])
        );

        // Paginer les formations
        $formations_data = $this->formationService->paginate($formations_params);

        // Récupérer les statistiques et les champs filtrables
        $formations_stats = $this->formationService->getformationStats();
        $this->viewState->set('stats.formation.stats'  , $formations_stats);
        $formations_filters = $this->formationService->getFieldsFilterable();
        $formation_instance =  $this->formationService->createInstance();
        
        $partialViewName =  $partialViewName = $this->getService()->getPartialViewName($viewType);

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($partialViewName, compact('viewTypes','formations_data', 'formations_stats', 'formations_filters','formation_instance'))->render();
        }

        return view('PkgAutoformation::formation.index', compact('viewTypes','viewType','formations_data', 'formations_stats', 'formations_filters','formation_instance'));
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.formation.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemFormation = $this->formationService->createInstance();
        
        // scopeDataInEditContext
        $value = $itemFormation->getNestedValue('1');
        $key = 'scope.formationOfficiel.is_officiel';
        $this->viewState->set($key, $value);

        $competences = $this->competenceService->all();
        $technologies = $this->technologyService->all();
        $formateurs = $this->formateurService->all();
        $formations = $this->formationService->all();
        $filieres = $this->filiereService->all();

        if (request()->ajax()) {
            return view('PkgAutoformation::formation._fields', compact('itemFormation', 'technologies', 'competences', 'formateurs', 'formations', 'filieres'));
        }
        return view('PkgAutoformation::formation.create', compact('itemFormation', 'technologies', 'competences', 'formateurs', 'formations', 'filieres'));
    }
    public function store(FormationRequest $request) {
        $validatedData = $request->validated();
        $formation = $this->formationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $formation,
                'modelName' => __('PkgAutoformation::formation.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $formation->id]
            );
        }

        return redirect()->route('formations.edit',['formation' => $formation->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $formation,
                'modelName' => __('PkgAutoformation::formation.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('formation.edit_' . $id);


        $itemFormation = $this->formationService->find($id);
        $this->authorize('view', $itemFormation);

        // scopeDataInEditContext
        $value = $itemFormation->getNestedValue('1');
        $key = 'scope.formationOfficiel.is_officiel';
        $this->viewState->set($key, $value);

        $competences = $this->competenceService->all();
        $technologies = $this->technologyService->all();
        $formateurs = $this->formateurService->all();
        $formations = $this->formationService->all();
        $filieres = $this->filiereService->all();
        

        $this->viewState->set('scope.formation.formation_officiel_id', $id);

        // scopeDataInEditContext
        $value = $itemFormation->getNestedValue('1');
        $key = 'scope.formationOfficiel.is_officiel';
        $this->viewState->set($key, $value);

        $formationService =  new FormationService();
        $formations_data =  $formationService->paginate();
        $formations_stats = $formationService->getformationStats();
        $formations_filters = $formationService->getFieldsFilterable();
        $formation_instance =  $formationService->createInstance();

        $this->viewState->set('scope.chapitre.formation_id', $id);


        $chapitreService =  new ChapitreService();
        $chapitres_data =  $chapitreService->paginate();
        $chapitres_stats = $chapitreService->getchapitreStats();
        $chapitres_filters = $chapitreService->getFieldsFilterable();
        $chapitre_instance =  $chapitreService->createInstance();

        $this->viewState->set('scope.realisationFormation.formation_id', $id);


        $realisationFormationService =  new RealisationFormationService();
        $realisationFormations_data =  $realisationFormationService->paginate();
        $realisationFormations_stats = $realisationFormationService->getrealisationFormationStats();
        $realisationFormations_filters = $realisationFormationService->getFieldsFilterable();
        $realisationFormation_instance =  $realisationFormationService->createInstance();

        if (request()->ajax()) {
            return view('PkgAutoformation::formation._edit', compact('itemFormation', 'technologies', 'competences', 'formateurs', 'formations', 'formations_data', 'chapitres_data', 'realisationFormations_data', 'formations_stats', 'chapitres_stats', 'realisationFormations_stats', 'formations_filters', 'chapitres_filters', 'realisationFormations_filters', 'formation_instance', 'chapitre_instance', 'realisationFormation_instance', 'filieres'));
        }

        return view('PkgAutoformation::formation.edit', compact('itemFormation', 'technologies', 'competences', 'formateurs', 'formations', 'formations_data', 'chapitres_data', 'realisationFormations_data', 'formations_stats', 'chapitres_stats', 'realisationFormations_stats', 'formations_filters', 'chapitres_filters', 'realisationFormations_filters', 'formation_instance', 'chapitre_instance', 'realisationFormation_instance', 'filieres'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('formation.edit_' . $id);


        $itemFormation = $this->formationService->find($id);
        $this->authorize('edit', $itemFormation);

        // scopeDataInEditContext
        $value = $itemFormation->getNestedValue('1');
        $key = 'scope.formationOfficiel.is_officiel';
        $this->viewState->set($key, $value);

        $competences = $this->competenceService->all();
        $technologies = $this->technologyService->all();
        $formateurs = $this->formateurService->all();
        $formations = $this->formationService->all();
        $filieres = $this->filiereService->all();


        $this->viewState->set('scope.formation.formation_officiel_id', $id);
        
        // scopeDataInEditContext
        $value = $itemFormation->getNestedValue('1');
        $key = 'scope.formationOfficiel.is_officiel';
        $this->viewState->set($key, $value);

        $formationService =  new FormationService();
        $formations_data =  $formationService->paginate();
        $formations_stats = $formationService->getformationStats();
        $this->viewState->set('stats.formation.stats'  , $formations_stats);
        $formations_filters = $formationService->getFieldsFilterable();
        $formation_instance =  $formationService->createInstance();

        $this->viewState->set('scope.chapitre.formation_id', $id);
        

        $chapitreService =  new ChapitreService();
        $chapitres_data =  $chapitreService->paginate();
        $chapitres_stats = $chapitreService->getchapitreStats();
        $this->viewState->set('stats.chapitre.stats'  , $chapitres_stats);
        $chapitres_filters = $chapitreService->getFieldsFilterable();
        $chapitre_instance =  $chapitreService->createInstance();

        $this->viewState->set('scope.realisationFormation.formation_id', $id);
        

        $realisationFormationService =  new RealisationFormationService();
        $realisationFormations_data =  $realisationFormationService->paginate();
        $realisationFormations_stats = $realisationFormationService->getrealisationFormationStats();
        $this->viewState->set('stats.realisationFormation.stats'  , $realisationFormations_stats);
        $realisationFormations_filters = $realisationFormationService->getFieldsFilterable();
        $realisationFormation_instance =  $realisationFormationService->createInstance();

        if (request()->ajax()) {
            return view('PkgAutoformation::formation._edit', compact('itemFormation', 'technologies', 'competences', 'formateurs', 'formations', 'formations_data', 'chapitres_data', 'realisationFormations_data', 'formations_stats', 'chapitres_stats', 'realisationFormations_stats', 'formations_filters', 'chapitres_filters', 'realisationFormations_filters', 'formation_instance', 'chapitre_instance', 'realisationFormation_instance', 'filieres'));
        }

        return view('PkgAutoformation::formation.edit', compact('itemFormation', 'technologies', 'competences', 'formateurs', 'formations', 'formations_data', 'chapitres_data', 'realisationFormations_data', 'formations_stats', 'chapitres_stats', 'realisationFormations_stats', 'formations_filters', 'chapitres_filters', 'realisationFormations_filters', 'formation_instance', 'chapitre_instance', 'realisationFormation_instance', 'filieres'));

    }
    public function update(FormationRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $formation = $this->formationService->find($id);
        $this->authorize('update', $formation);

        $validatedData = $request->validated();
        $formation = $this->formationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $formation,
                'modelName' =>  __('PkgAutoformation::formation.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $formation->id]
            );
        }

        return redirect()->route('formations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $formation,
                'modelName' =>  __('PkgAutoformation::formation.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $formation = $this->formationService->find($id);
        $this->authorize('delete', $formation);

        $formation = $this->formationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $formation,
                'modelName' =>  __('PkgAutoformation::formation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('formations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $formation,
                'modelName' =>  __('PkgAutoformation::formation.singular')
                ])
        );

    }

    public function export($format)
    {
        $formations_data = $this->formationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new FormationExport($formations_data,'csv'), 'formation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new FormationExport($formations_data,'xlsx'), 'formation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new FormationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('formations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('formations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutoformation::formation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFormations()
    {
        $formations = $this->formationService->all();
        return response()->json($formations);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $formation = $this->formationService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedFormation = $this->formationService->dataCalcul($formation);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedFormation
        ]);
    }
    

}