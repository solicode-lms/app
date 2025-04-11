<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Controllers\Base;
use Modules\PkgAutoformation\Services\ChapitreService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgAutoformation\Services\FormationService;
use Modules\PkgCompetences\Services\NiveauCompetenceService;
use Modules\PkgAutoformation\Services\RealisationChapitreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutoformation\App\Requests\ChapitreRequest;
use Modules\PkgAutoformation\Models\Chapitre;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutoformation\App\Exports\ChapitreExport;
use Modules\PkgAutoformation\App\Imports\ChapitreImport;
use Modules\Core\Services\ContextState;

class BaseChapitreController extends AdminController
{
    protected $chapitreService;
    protected $formateurService;
    protected $formationService;
    protected $niveauCompetenceService;

    public function __construct(ChapitreService $chapitreService, FormateurService $formateurService, FormationService $formationService, NiveauCompetenceService $niveauCompetenceService) {
        parent::__construct();
        $this->service  =  $chapitreService;
        $this->chapitreService = $chapitreService;
        $this->formateurService = $formateurService;
        $this->formationService = $formationService;
        $this->niveauCompetenceService = $niveauCompetenceService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('chapitre.index');
        



         // Extraire les paramètres de recherche, pagination, filtres
        $chapitres_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'chapitres_search',
                $this->viewState->get("filter.chapitre.chapitres_search")
            )],
            $request->except(['chapitres_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->chapitreService->prepareDataForIndexView($chapitres_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgAutoformation::chapitre._index', $chapitre_compact_value)->render();
            }else{
                return view($chapitre_partialViewName, $chapitre_compact_value)->render();
            }
        }

        return view('PkgAutoformation::chapitre.index', $chapitre_compact_value);
    }
    public function create() {


        $itemChapitre = $this->chapitreService->createInstance();
        

        $formations = $this->formationService->all();
        $niveauCompetences = $this->niveauCompetenceService->all();
        $formateurs = $this->formateurService->all();
        $chapitres = $this->chapitreService->all();

        if (request()->ajax()) {
            return view('PkgAutoformation::chapitre._fields', compact('itemChapitre', 'chapitres', 'formateurs', 'formations', 'niveauCompetences'));
        }
        return view('PkgAutoformation::chapitre.create', compact('itemChapitre', 'chapitres', 'formateurs', 'formations', 'niveauCompetences'));
    }
    public function store(ChapitreRequest $request) {
        $validatedData = $request->validated();
        $chapitre = $this->chapitreService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $chapitre,
                'modelName' => __('PkgAutoformation::chapitre.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $chapitre->id]
            );
        }

        return redirect()->route('chapitres.edit',['chapitre' => $chapitre->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $chapitre,
                'modelName' => __('PkgAutoformation::chapitre.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('chapitre.edit_' . $id);


        $itemChapitre = $this->chapitreService->edit($id);


        $formations = $this->formationService->all();
        $niveauCompetences = $this->niveauCompetenceService->all();
        $formateurs = $this->formateurService->all();
        $chapitres = $this->chapitreService->all();


        $this->viewState->set('scope.chapitre.chapitre_officiel_id', $id);
        

        $chapitreService =  new ChapitreService();
        $chapitres_view_data = $chapitreService->prepareDataForIndexView();
        extract($chapitres_view_data);

        $this->viewState->set('scope.realisationChapitre.chapitre_id', $id);
        

        $realisationChapitreService =  new RealisationChapitreService();
        $realisationChapitres_view_data = $realisationChapitreService->prepareDataForIndexView();
        extract($realisationChapitres_view_data);

        if (request()->ajax()) {
            return view('PkgAutoformation::chapitre._edit', array_merge(compact('itemChapitre','chapitres', 'formateurs', 'formations', 'niveauCompetences'),$chapitre_compact_value, $realisationChapitre_compact_value));
        }

        return view('PkgAutoformation::chapitre.edit', array_merge(compact('itemChapitre','chapitres', 'formateurs', 'formations', 'niveauCompetences'),$chapitre_compact_value, $realisationChapitre_compact_value));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('chapitre.edit_' . $id);


        $itemChapitre = $this->chapitreService->edit($id);


        $formations = $this->formationService->all();
        $niveauCompetences = $this->niveauCompetenceService->all();
        $formateurs = $this->formateurService->all();
        $chapitres = $this->chapitreService->all();


        $this->viewState->set('scope.chapitre.chapitre_officiel_id', $id);
        

        $chapitreService =  new ChapitreService();
        $chapitres_view_data = $chapitreService->prepareDataForIndexView();
        extract($chapitres_view_data);

        $this->viewState->set('scope.realisationChapitre.chapitre_id', $id);
        

        $realisationChapitreService =  new RealisationChapitreService();
        $realisationChapitres_view_data = $realisationChapitreService->prepareDataForIndexView();
        extract($realisationChapitres_view_data);

        if (request()->ajax()) {
            return view('PkgAutoformation::chapitre._edit', array_merge(compact('itemChapitre','chapitres', 'formateurs', 'formations', 'niveauCompetences'),$chapitre_compact_value, $realisationChapitre_compact_value));
        }

        return view('PkgAutoformation::chapitre.edit', array_merge(compact('itemChapitre','chapitres', 'formateurs', 'formations', 'niveauCompetences'),$chapitre_compact_value, $realisationChapitre_compact_value));


    }
    public function update(ChapitreRequest $request, string $id) {

        $validatedData = $request->validated();
        $chapitre = $this->chapitreService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $chapitre,
                'modelName' =>  __('PkgAutoformation::chapitre.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $chapitre->id]
            );
        }

        return redirect()->route('chapitres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $chapitre,
                'modelName' =>  __('PkgAutoformation::chapitre.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $chapitre = $this->chapitreService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $chapitre,
                'modelName' =>  __('PkgAutoformation::chapitre.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('chapitres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $chapitre,
                'modelName' =>  __('PkgAutoformation::chapitre.singular')
                ])
        );

    }

    public function export($format)
    {
        $chapitres_data = $this->chapitreService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new ChapitreExport($chapitres_data,'csv'), 'chapitre_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new ChapitreExport($chapitres_data,'xlsx'), 'chapitre_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new ChapitreImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('chapitres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('chapitres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutoformation::chapitre.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getChapitres()
    {
        $chapitres = $this->chapitreService->all();
        return response()->json($chapitres);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $chapitre = $this->chapitreService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedChapitre = $this->chapitreService->dataCalcul($chapitre);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedChapitre
        ]);
    }
    

}