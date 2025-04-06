<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgFormation\Services\FiliereService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgCreationProjet\Services\LivrableService;
use Modules\PkgGestionTaches\Services\TacheService;
use Modules\PkgCreationProjet\Services\ResourceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationProjet\App\Requests\ProjetRequest;
use Modules\PkgCreationProjet\Models\Projet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\ProjetExport;
use Modules\PkgCreationProjet\App\Imports\ProjetImport;
use Modules\Core\Services\ContextState;

class BaseProjetController extends AdminController
{
    protected $projetService;
    protected $filiereService;
    protected $formateurService;

    public function __construct(ProjetService $projetService, FiliereService $filiereService, FormateurService $formateurService) {
        parent::__construct();
        $this->service  =  $projetService;
        $this->projetService = $projetService;
        $this->filiereService = $filiereService;
        $this->formateurService = $formateurService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('projet.index');
        
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.projet.formateur_id') == null){
           $this->viewState->init('filter.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('scope.projet.affectationProjets.realisationProjets.apprenant_id') == null){
           $this->viewState->init('scope.projet.affectationProjets.realisationProjets.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $projets_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'projets_search',
                $this->viewState->get("filter.projet.projets_search")
            )],
            $request->except(['projets_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->projetService->prepareDataForIndexView($projets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($projet_partialViewName, $projet_compact_value)->render();
        }

        return view('PkgCreationProjet::projet.index', $projet_compact_value);
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.projet.affectationProjets.realisationProjets.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }


        $itemProjet = $this->projetService->createInstance();
        

        $formateurs = $this->formateurService->all();
        $filieres = $this->filiereService->all();

        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._fields', compact('itemProjet', 'filieres', 'formateurs'));
        }
        return view('PkgCreationProjet::projet.create', compact('itemProjet', 'filieres', 'formateurs'));
    }
    public function store(ProjetRequest $request) {
        $validatedData = $request->validated();
        $projet = $this->projetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $projet,
                'modelName' => __('PkgCreationProjet::projet.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $projet->id]
            );
        }

        return redirect()->route('projets.edit',['projet' => $projet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $projet,
                'modelName' => __('PkgCreationProjet::projet.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('projet.edit_' . $id);


        $itemProjet = $this->projetService->find($id);
        $this->authorize('view', $itemProjet);


        $formateurs = $this->formateurService->all();
        $filieres = $this->filiereService->all();
        

        $this->viewState->set('scope.transfertCompetence.projet_id', $id);

        // scopeDataInEditContext
        $value = $itemProjet->getNestedValue('filiere_id');
        $key = 'scope.competence.module.filiere_id';
        $this->viewState->set($key, $value);
        // scopeDataInEditContext
        $value = $itemProjet->getNestedValue('formateur_id');
        $key = 'scope.niveauDifficulte.formateur_id';
        $this->viewState->set($key, $value);

        $transfertCompetenceService =  new TransfertCompetenceService();
        $transfertCompetences_view_data = $transfertCompetenceService->prepareDataForIndexView();
        extract($transfertCompetences_view_data);

        $this->viewState->set('scope.affectationProjet.projet_id', $id);

        // scopeDataInEditContext
        $value = $itemProjet->getNestedValue('formateur_id');
        $key = 'scope.groupe.formateurs.formateur_id';
        $this->viewState->set($key, $value);

        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_view_data = $affectationProjetService->prepareDataForIndexView();
        extract($affectationProjets_view_data);

        $this->viewState->set('scope.livrable.projet_id', $id);


        $livrableService =  new LivrableService();
        $livrables_view_data = $livrableService->prepareDataForIndexView();
        extract($livrables_view_data);

        $this->viewState->set('scope.tache.projet_id', $id);


        $tacheService =  new TacheService();
        $taches_view_data = $tacheService->prepareDataForIndexView();
        extract($taches_view_data);

        $this->viewState->set('scope.resource.projet_id', $id);


        $resourceService =  new ResourceService();
        $resources_view_data = $resourceService->prepareDataForIndexView();
        extract($resources_view_data);

        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._edit', array_merge(compact('itemProjet'),$filieres, $formateurs));
        }

        return view('PkgCreationProjet::projet.edit', array_merge(compact('itemProjet'),$filieres, $formateurs));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('projet.edit_' . $id);


        $itemProjet = $this->projetService->find($id);
        $this->authorize('edit', $itemProjet);


        $formateurs = $this->formateurService->all();
        $filieres = $this->filiereService->all();


        $this->viewState->set('scope.transfertCompetence.projet_id', $id);
        
        // scopeDataInEditContext
        $value = $itemProjet->getNestedValue('filiere_id');
        $key = 'scope.competence.module.filiere_id';
        $this->viewState->set($key, $value);
        // scopeDataInEditContext
        $value = $itemProjet->getNestedValue('formateur_id');
        $key = 'scope.niveauDifficulte.formateur_id';
        $this->viewState->set($key, $value);

        $transfertCompetenceService =  new TransfertCompetenceService();
        $transfertCompetences_view_data = $transfertCompetenceService->prepareDataForIndexView();
        extract($transfertCompetences_view_data);

        $this->viewState->set('scope.affectationProjet.projet_id', $id);
        
        // scopeDataInEditContext
        $value = $itemProjet->getNestedValue('formateur_id');
        $key = 'scope.groupe.formateurs.formateur_id';
        $this->viewState->set($key, $value);

        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_view_data = $affectationProjetService->prepareDataForIndexView();
        extract($affectationProjets_view_data);

        $this->viewState->set('scope.livrable.projet_id', $id);
        

        $livrableService =  new LivrableService();
        $livrables_view_data = $livrableService->prepareDataForIndexView();
        extract($livrables_view_data);

        $this->viewState->set('scope.tache.projet_id', $id);
        

        $tacheService =  new TacheService();
        $taches_view_data = $tacheService->prepareDataForIndexView();
        extract($taches_view_data);

        $this->viewState->set('scope.resource.projet_id', $id);
        

        $resourceService =  new ResourceService();
        $resources_view_data = $resourceService->prepareDataForIndexView();
        extract($resources_view_data);

        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._edit', array_merge(compact('itemProjet','filieres', 'formateurs'),$transfertCompetence_compact_value, $affectationProjet_compact_value, $livrable_compact_value, $tache_compact_value, $resource_compact_value));
        }

        return view('PkgCreationProjet::projet.edit', array_merge(compact('itemProjet','filieres', 'formateurs'),$transfertCompetence_compact_value, $affectationProjet_compact_value, $livrable_compact_value, $tache_compact_value, $resource_compact_value));

    }
    public function update(ProjetRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $projet = $this->projetService->find($id);
        $this->authorize('update', $projet);

        $validatedData = $request->validated();
        $projet = $this->projetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $projet->id]
            );
        }

        return redirect()->route('projets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $projet = $this->projetService->find($id);
        $this->authorize('delete', $projet);

        $projet = $this->projetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('projets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')
                ])
        );

    }

    public function export($format)
    {
        $projets_data = $this->projetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new ProjetExport($projets_data,'csv'), 'projet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new ProjetExport($projets_data,'xlsx'), 'projet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new ProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('projets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('projets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::projet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getProjets()
    {
        $projets = $this->projetService->all();
        return response()->json($projets);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $projet = $this->projetService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedProjet = $this->projetService->dataCalcul($projet);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedProjet
        ]);
    }
    

}