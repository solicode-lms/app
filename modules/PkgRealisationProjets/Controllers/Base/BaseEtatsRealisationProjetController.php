<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService;
use Modules\PkgFormation\Services\FormateurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationProjets\App\Requests\EtatsRealisationProjetRequest;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\EtatsRealisationProjetExport;
use Modules\PkgRealisationProjets\App\Imports\EtatsRealisationProjetImport;
use Modules\Core\Services\ContextState;

class BaseEtatsRealisationProjetController extends AdminController
{
    protected $etatsRealisationProjetService;
    protected $formateurService;

    public function __construct(EtatsRealisationProjetService $etatsRealisationProjetService, FormateurService $formateurService) {
        parent::__construct();
        $this->service  =  $etatsRealisationProjetService;
        $this->etatsRealisationProjetService = $etatsRealisationProjetService;
        $this->formateurService = $formateurService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('etatsRealisationProjet.index');
        
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.etatsRealisationProjet.formateur_id') == null){
           $this->viewState->init('scope.etatsRealisationProjet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $etatsRealisationProjets_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'etatsRealisationProjets_search',
                $this->viewState->get("filter.etatsRealisationProjet.etatsRealisationProjets_search")
            )],
            $request->except(['etatsRealisationProjets_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->etatsRealisationProjetService->prepareDataForIndexView($etatsRealisationProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($etatsRealisationProjet_partialViewName, $etatsRealisationProjet_compact_value)->render();
        }

        return view('PkgRealisationProjets::etatsRealisationProjet.index', $etatsRealisationProjet_compact_value);
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.etatsRealisationProjet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemEtatsRealisationProjet = $this->etatsRealisationProjetService->createInstance();
        

        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgRealisationProjets::etatsRealisationProjet._fields', compact('itemEtatsRealisationProjet', 'formateurs'));
        }
        return view('PkgRealisationProjets::etatsRealisationProjet.create', compact('itemEtatsRealisationProjet', 'formateurs'));
    }
    public function store(EtatsRealisationProjetRequest $request) {
        $validatedData = $request->validated();
        $etatsRealisationProjet = $this->etatsRealisationProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' => __('PkgRealisationProjets::etatsRealisationProjet.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $etatsRealisationProjet->id]
            );
        }

        return redirect()->route('etatsRealisationProjets.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' => __('PkgRealisationProjets::etatsRealisationProjet.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('etatsRealisationProjet.edit_' . $id);


        $itemEtatsRealisationProjet = $this->etatsRealisationProjetService->edit($id);
        $this->authorize('view', $itemEtatsRealisationProjet);


        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgRealisationProjets::etatsRealisationProjet._fields', array_merge(compact('itemEtatsRealisationProjet','formateurs'),));
        }

        return view('PkgRealisationProjets::etatsRealisationProjet.edit', array_merge(compact('itemEtatsRealisationProjet','formateurs'),));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('etatsRealisationProjet.edit_' . $id);


        $itemEtatsRealisationProjet = $this->etatsRealisationProjetService->edit($id);
        $this->authorize('edit', $itemEtatsRealisationProjet);


        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgRealisationProjets::etatsRealisationProjet._fields', array_merge(compact('itemEtatsRealisationProjet','formateurs'),));
        }

        return view('PkgRealisationProjets::etatsRealisationProjet.edit', array_merge(compact('itemEtatsRealisationProjet','formateurs'),));


    }
    public function update(EtatsRealisationProjetRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $etatsRealisationProjet = $this->etatsRealisationProjetService->find($id);
        $this->authorize('update', $etatsRealisationProjet);

        $validatedData = $request->validated();
        $etatsRealisationProjet = $this->etatsRealisationProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' =>  __('PkgRealisationProjets::etatsRealisationProjet.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $etatsRealisationProjet->id]
            );
        }

        return redirect()->route('etatsRealisationProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' =>  __('PkgRealisationProjets::etatsRealisationProjet.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $etatsRealisationProjet = $this->etatsRealisationProjetService->find($id);
        $this->authorize('delete', $etatsRealisationProjet);

        $etatsRealisationProjet = $this->etatsRealisationProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' =>  __('PkgRealisationProjets::etatsRealisationProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('etatsRealisationProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $etatsRealisationProjet,
                'modelName' =>  __('PkgRealisationProjets::etatsRealisationProjet.singular')
                ])
        );

    }

    public function export($format)
    {
        $etatsRealisationProjets_data = $this->etatsRealisationProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new EtatsRealisationProjetExport($etatsRealisationProjets_data,'csv'), 'etatsRealisationProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new EtatsRealisationProjetExport($etatsRealisationProjets_data,'xlsx'), 'etatsRealisationProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new EtatsRealisationProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('etatsRealisationProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('etatsRealisationProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationProjets::etatsRealisationProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getEtatsRealisationProjets()
    {
        $etatsRealisationProjets = $this->etatsRealisationProjetService->all();
        return response()->json($etatsRealisationProjets);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $etatsRealisationProjet = $this->etatsRealisationProjetService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedEtatsRealisationProjet = $this->etatsRealisationProjetService->dataCalcul($etatsRealisationProjet);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedEtatsRealisationProjet
        ]);
    }
    

}