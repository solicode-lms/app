<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgFormation\Services\FiliereService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgGestionTaches\Services\TacheService;
use Modules\PkgCreationProjet\Services\LivrableService;
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

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('projet.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('projet');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.projet.formateur_id') == null){
           $this->viewState->init('filter.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('scope.projet.affectationProjets.realisationProjets.apprenant_id') == null){
           $this->viewState->init('scope.projet.affectationProjets.realisationProjets.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $projets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'projets_search',
                $this->viewState->get("filter.projet.projets_search")
            )],
            $request->except(['projets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->projetService->prepareDataForIndexView($projets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCreationProjet::projet._index', $projet_compact_value)->render();
            }else{
                return view($projet_partialViewName, $projet_compact_value)->render();
            }
        }

        return view('PkgCreationProjet::projet.index', $projet_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.projet.affectationProjets.realisationProjets.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }


        $itemProjet = $this->projetService->createInstance();
        

        $filieres = $this->filiereService->all();
        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._fields', compact('itemProjet', 'filieres', 'formateurs'));
        }
        return view('PkgCreationProjet::projet.create', compact('itemProjet', 'filieres', 'formateurs'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $projet_ids = $request->input('ids', []);

        if (!is_array($projet_ids) || count($projet_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.projet.affectationProjets.realisationProjets.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }
 
         $itemProjet = $this->projetService->find($projet_ids[0]);
         
 
        $filieres = $this->filiereService->all();
        $formateurs = $this->formateurService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemProjet = $this->projetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._fields', compact('bulkEdit', 'projet_ids', 'itemProjet', 'filieres', 'formateurs'));
        }
        return view('PkgCreationProjet::projet.bulk-edit', compact('bulkEdit', 'projet_ids', 'itemProjet', 'filieres', 'formateurs'));
    }
    /**
     */
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
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('projet.show_' . $id);

        $itemProjet = $this->projetService->edit($id);
        $this->authorize('view', $itemProjet);


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

        $this->viewState->set('scope.tache.projet_id', $id);
        

        $tacheService =  new TacheService();
        $taches_view_data = $tacheService->prepareDataForIndexView();
        extract($taches_view_data);

        $this->viewState->set('scope.livrable.projet_id', $id);
        

        $livrableService =  new LivrableService();
        $livrables_view_data = $livrableService->prepareDataForIndexView();
        extract($livrables_view_data);

        $this->viewState->set('scope.resource.projet_id', $id);
        

        $resourceService =  new ResourceService();
        $resources_view_data = $resourceService->prepareDataForIndexView();
        extract($resources_view_data);

        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._show', array_merge(compact('itemProjet'),$transfertCompetence_compact_value, $tache_compact_value, $livrable_compact_value, $resource_compact_value));
        }

        return view('PkgCreationProjet::projet.show', array_merge(compact('itemProjet'),$transfertCompetence_compact_value, $tache_compact_value, $livrable_compact_value, $resource_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('projet.edit_' . $id);


        $itemProjet = $this->projetService->edit($id);
        $this->authorize('edit', $itemProjet);


        $filieres = $this->filiereService->all();
        $formateurs = $this->formateurService->all();


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

        $this->viewState->set('scope.tache.projet_id', $id);
        

        $tacheService =  new TacheService();
        $taches_view_data = $tacheService->prepareDataForIndexView();
        extract($taches_view_data);

        $this->viewState->set('scope.livrable.projet_id', $id);
        

        $livrableService =  new LivrableService();
        $livrables_view_data = $livrableService->prepareDataForIndexView();
        extract($livrables_view_data);

        $this->viewState->set('scope.resource.projet_id', $id);
        

        $resourceService =  new ResourceService();
        $resources_view_data = $resourceService->prepareDataForIndexView();
        extract($resources_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._edit', array_merge(compact('bulkEdit' , 'itemProjet','filieres', 'formateurs'),$transfertCompetence_compact_value, $affectationProjet_compact_value, $tache_compact_value, $livrable_compact_value, $resource_compact_value));
        }

        return view('PkgCreationProjet::projet.edit', array_merge(compact('itemProjet','filieres', 'formateurs'),$transfertCompetence_compact_value, $affectationProjet_compact_value, $tache_compact_value, $livrable_compact_value, $resource_compact_value));


    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $projet_ids = $request->input('projet_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($projet_ids) || count($projet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($projet_ids as $id) {
            $entity = $this->projetService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->projetService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->projetService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $projet_ids = $request->input('ids', []);
        if (!is_array($projet_ids) || count($projet_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($projet_ids as $id) {
            $entity = $this->projetService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $projet = $this->projetService->find($id);
            $this->authorize('delete', $projet);
            $this->projetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($projet_ids) . ' éléments',
            'modelName' => __('PkgCreationProjet::projet.plural')
        ]));
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
    


    /**
     * @DynamicPermissionIgnore
     * Met à jour les attributs, il est utilisé par type View : Widgets
     */
    public function updateAttributes(Request $request)
    {
        // Autorisation dynamique basée sur le nom du contrôleur
        $this->authorizeAction('update');
    
        $updatableFields = $this->service->getFieldsEditable();
        $projetRequest = new ProjetRequest();
        $fullRules = $projetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:projets,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.',null, 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}