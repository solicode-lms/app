<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutoformation\Controllers\Base;
use Modules\PkgAutoformation\Services\RealisationChapitreService;
use Modules\PkgAutoformation\Services\ChapitreService;
use Modules\PkgAutoformation\Services\EtatChapitreService;
use Modules\PkgAutoformation\Services\RealisationFormationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgAutoformation\App\Requests\RealisationChapitreRequest;
use Modules\PkgAutoformation\Models\RealisationChapitre;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgAutoformation\App\Exports\RealisationChapitreExport;
use Modules\PkgAutoformation\App\Imports\RealisationChapitreImport;
use Modules\Core\Services\ContextState;

class BaseRealisationChapitreController extends AdminController
{
    protected $realisationChapitreService;
    protected $chapitreService;
    protected $etatChapitreService;
    protected $realisationFormationService;

    public function __construct(RealisationChapitreService $realisationChapitreService, ChapitreService $chapitreService, EtatChapitreService $etatChapitreService, RealisationFormationService $realisationFormationService) {
        parent::__construct();
        $this->service  =  $realisationChapitreService;
        $this->realisationChapitreService = $realisationChapitreService;
        $this->chapitreService = $chapitreService;
        $this->etatChapitreService = $etatChapitreService;
        $this->realisationFormationService = $realisationFormationService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('realisationChapitre.index');
        



         // Extraire les paramètres de recherche, pagination, filtres
        $realisationChapitres_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'realisationChapitres_search',
                $this->viewState->get("filter.realisationChapitre.realisationChapitres_search")
            )],
            $request->except(['realisationChapitres_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->realisationChapitreService->prepareDataForIndexView($realisationChapitres_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgAutoformation::realisationChapitre._index', $realisationChapitre_compact_value)->render();
            }else{
                return view($realisationChapitre_partialViewName, $realisationChapitre_compact_value)->render();
            }
        }

        return view('PkgAutoformation::realisationChapitre.index', $realisationChapitre_compact_value);
    }
    public function create() {


        $itemRealisationChapitre = $this->realisationChapitreService->createInstance();
        

        $chapitres = $this->chapitreService->all();
        $realisationFormations = $this->realisationFormationService->all();
        $etatChapitres = $this->etatChapitreService->all();

        if (request()->ajax()) {
            return view('PkgAutoformation::realisationChapitre._fields', compact('itemRealisationChapitre', 'chapitres', 'etatChapitres', 'realisationFormations'));
        }
        return view('PkgAutoformation::realisationChapitre.create', compact('itemRealisationChapitre', 'chapitres', 'etatChapitres', 'realisationFormations'));
    }
    public function store(RealisationChapitreRequest $request) {
        $validatedData = $request->validated();
        $realisationChapitre = $this->realisationChapitreService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' => __('PkgAutoformation::realisationChapitre.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $realisationChapitre->id]
            );
        }

        return redirect()->route('realisationChapitres.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' => __('PkgAutoformation::realisationChapitre.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('realisationChapitre.edit_' . $id);


        $itemRealisationChapitre = $this->realisationChapitreService->edit($id);


        $chapitres = $this->chapitreService->all();
        $realisationFormations = $this->realisationFormationService->all();
        $etatChapitres = $this->etatChapitreService->all();


        if (request()->ajax()) {
            return view('PkgAutoformation::realisationChapitre._fields', array_merge(compact('itemRealisationChapitre','chapitres', 'etatChapitres', 'realisationFormations'),));
        }

        return view('PkgAutoformation::realisationChapitre.edit', array_merge(compact('itemRealisationChapitre','chapitres', 'etatChapitres', 'realisationFormations'),));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationChapitre.edit_' . $id);


        $itemRealisationChapitre = $this->realisationChapitreService->edit($id);


        $chapitres = $this->chapitreService->all();
        $realisationFormations = $this->realisationFormationService->all();
        $etatChapitres = $this->etatChapitreService->all();


        if (request()->ajax()) {
            return view('PkgAutoformation::realisationChapitre._fields', array_merge(compact('itemRealisationChapitre','chapitres', 'etatChapitres', 'realisationFormations'),));
        }

        return view('PkgAutoformation::realisationChapitre.edit', array_merge(compact('itemRealisationChapitre','chapitres', 'etatChapitres', 'realisationFormations'),));


    }
    public function update(RealisationChapitreRequest $request, string $id) {

        $validatedData = $request->validated();
        $realisationChapitre = $this->realisationChapitreService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' =>  __('PkgAutoformation::realisationChapitre.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $realisationChapitre->id]
            );
        }

        return redirect()->route('realisationChapitres.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' =>  __('PkgAutoformation::realisationChapitre.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $realisationChapitre = $this->realisationChapitreService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' =>  __('PkgAutoformation::realisationChapitre.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('realisationChapitres.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationChapitre,
                'modelName' =>  __('PkgAutoformation::realisationChapitre.singular')
                ])
        );

    }

    public function export($format)
    {
        $realisationChapitres_data = $this->realisationChapitreService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationChapitreExport($realisationChapitres_data,'csv'), 'realisationChapitre_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationChapitreExport($realisationChapitres_data,'xlsx'), 'realisationChapitre_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationChapitreImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationChapitres.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationChapitres.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgAutoformation::realisationChapitre.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationChapitres()
    {
        $realisationChapitres = $this->realisationChapitreService->all();
        return response()->json($realisationChapitres);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $realisationChapitre = $this->realisationChapitreService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedRealisationChapitre = $this->realisationChapitreService->dataCalcul($realisationChapitre);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedRealisationChapitre
        ]);
    }
    

}