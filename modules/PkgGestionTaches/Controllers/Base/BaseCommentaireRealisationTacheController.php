<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgGestionTaches\Controllers\Base;
use Modules\PkgGestionTaches\Services\CommentaireRealisationTacheService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgGestionTaches\Services\RealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgGestionTaches\App\Requests\CommentaireRealisationTacheRequest;
use Modules\PkgGestionTaches\Models\CommentaireRealisationTache;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgGestionTaches\App\Exports\CommentaireRealisationTacheExport;
use Modules\PkgGestionTaches\App\Imports\CommentaireRealisationTacheImport;
use Modules\Core\Services\ContextState;

class BaseCommentaireRealisationTacheController extends AdminController
{
    protected $commentaireRealisationTacheService;
    protected $apprenantService;
    protected $formateurService;
    protected $realisationTacheService;

    public function __construct(CommentaireRealisationTacheService $commentaireRealisationTacheService, ApprenantService $apprenantService, FormateurService $formateurService, RealisationTacheService $realisationTacheService) {
        parent::__construct();
        $this->service  =  $commentaireRealisationTacheService;
        $this->commentaireRealisationTacheService = $commentaireRealisationTacheService;
        $this->apprenantService = $apprenantService;
        $this->formateurService = $formateurService;
        $this->realisationTacheService = $realisationTacheService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('commentaireRealisationTache.index');
        



         // Extraire les paramètres de recherche, pagination, filtres
        $commentaireRealisationTaches_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'commentaireRealisationTaches_search',
                $this->viewState->get("filter.commentaireRealisationTache.commentaireRealisationTaches_search")
            )],
            $request->except(['commentaireRealisationTaches_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->commentaireRealisationTacheService->prepareDataForIndexView($commentaireRealisationTaches_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($commentaireRealisationTache_partialViewName, $commentaireRealisationTache_compact_value)->render();
        }

        return view('PkgGestionTaches::commentaireRealisationTache.index', $commentaireRealisationTache_compact_value);
    }
    public function create() {


        $itemCommentaireRealisationTache = $this->commentaireRealisationTacheService->createInstance();
        

        $realisationTaches = $this->realisationTacheService->all();
        $formateurs = $this->formateurService->all();
        $apprenants = $this->apprenantService->all();

        if (request()->ajax()) {
            return view('PkgGestionTaches::commentaireRealisationTache._fields', compact('itemCommentaireRealisationTache', 'apprenants', 'formateurs', 'realisationTaches'));
        }
        return view('PkgGestionTaches::commentaireRealisationTache.create', compact('itemCommentaireRealisationTache', 'apprenants', 'formateurs', 'realisationTaches'));
    }
    public function store(CommentaireRealisationTacheRequest $request) {
        $validatedData = $request->validated();
        $commentaireRealisationTache = $this->commentaireRealisationTacheService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $commentaireRealisationTache,
                'modelName' => __('PkgGestionTaches::commentaireRealisationTache.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $commentaireRealisationTache->id]
            );
        }

        return redirect()->route('commentaireRealisationTaches.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $commentaireRealisationTache,
                'modelName' => __('PkgGestionTaches::commentaireRealisationTache.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('commentaireRealisationTache.edit_' . $id);


        $itemCommentaireRealisationTache = $this->commentaireRealisationTacheService->find($id);


        $realisationTaches = $this->realisationTacheService->all();
        $formateurs = $this->formateurService->all();
        $apprenants = $this->apprenantService->all();


        if (request()->ajax()) {
            return view('PkgGestionTaches::commentaireRealisationTache._fields', array_merge(compact('itemCommentaireRealisationTache','apprenants', 'formateurs', 'realisationTaches'),));
        }

        return view('PkgGestionTaches::commentaireRealisationTache.edit', array_merge(compact('itemCommentaireRealisationTache','apprenants', 'formateurs', 'realisationTaches'),));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('commentaireRealisationTache.edit_' . $id);


        $itemCommentaireRealisationTache = $this->commentaireRealisationTacheService->find($id);


        $realisationTaches = $this->realisationTacheService->all();
        $formateurs = $this->formateurService->all();
        $apprenants = $this->apprenantService->all();


        if (request()->ajax()) {
            return view('PkgGestionTaches::commentaireRealisationTache._fields', array_merge(compact('itemCommentaireRealisationTache','apprenants', 'formateurs', 'realisationTaches'),));
        }

        return view('PkgGestionTaches::commentaireRealisationTache.edit', array_merge(compact('itemCommentaireRealisationTache','apprenants', 'formateurs', 'realisationTaches'),));

    }
    public function update(CommentaireRealisationTacheRequest $request, string $id) {

        $validatedData = $request->validated();
        $commentaireRealisationTache = $this->commentaireRealisationTacheService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $commentaireRealisationTache,
                'modelName' =>  __('PkgGestionTaches::commentaireRealisationTache.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $commentaireRealisationTache->id]
            );
        }

        return redirect()->route('commentaireRealisationTaches.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $commentaireRealisationTache,
                'modelName' =>  __('PkgGestionTaches::commentaireRealisationTache.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $commentaireRealisationTache = $this->commentaireRealisationTacheService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $commentaireRealisationTache,
                'modelName' =>  __('PkgGestionTaches::commentaireRealisationTache.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('commentaireRealisationTaches.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $commentaireRealisationTache,
                'modelName' =>  __('PkgGestionTaches::commentaireRealisationTache.singular')
                ])
        );

    }

    public function export($format)
    {
        $commentaireRealisationTaches_data = $this->commentaireRealisationTacheService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new CommentaireRealisationTacheExport($commentaireRealisationTaches_data,'csv'), 'commentaireRealisationTache_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new CommentaireRealisationTacheExport($commentaireRealisationTaches_data,'xlsx'), 'commentaireRealisationTache_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new CommentaireRealisationTacheImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('commentaireRealisationTaches.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('commentaireRealisationTaches.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgGestionTaches::commentaireRealisationTache.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getCommentaireRealisationTaches()
    {
        $commentaireRealisationTaches = $this->commentaireRealisationTacheService->all();
        return response()->json($commentaireRealisationTaches);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $commentaireRealisationTache = $this->commentaireRealisationTacheService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedCommentaireRealisationTache = $this->commentaireRealisationTacheService->dataCalcul($commentaireRealisationTache);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedCommentaireRealisationTache
        ]);
    }
    

}