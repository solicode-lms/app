<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgFormation\Services\SpecialiteService;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgCompetences\Services\ChapitreService;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgCreationTache\Services\PrioriteTacheService;
use Modules\PkgRealisationTache\Services\CommentaireRealisationTacheService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgFormation\App\Requests\FormateurRequest;
use Modules\PkgFormation\Models\Formateur;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgFormation\App\Exports\FormateurExport;
use Modules\PkgFormation\App\Imports\FormateurImport;
use Modules\Core\Services\ContextState;

class BaseFormateurController extends AdminController
{
    protected $formateurService;
    protected $groupeService;
    protected $specialiteService;
    protected $userService;

    public function __construct(FormateurService $formateurService, GroupeService $groupeService, SpecialiteService $specialiteService, UserService $userService) {
        parent::__construct();
        $this->service  =  $formateurService;
        $this->formateurService = $formateurService;
        $this->groupeService = $groupeService;
        $this->specialiteService = $specialiteService;
        $this->userService = $userService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('formateur.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('formateur');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $formateurs_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'formateurs_search',
                $this->viewState->get("filter.formateur.formateurs_search")
            )],
            $request->except(['formateurs_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->formateurService->prepareDataForIndexView($formateurs_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgFormation::formateur._index', $formateur_compact_value)->render();
            }else{
                return view($formateur_partialViewName, $formateur_compact_value)->render();
            }
        }

        return view('PkgFormation::formateur.index', $formateur_compact_value);
    }
    /**
     */
    public function create() {


        $itemFormateur = $this->formateurService->createInstance();
        

        $specialites = $this->specialiteService->all();
        $groupes = $this->groupeService->all();
        $users = $this->userService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgFormation::formateur._fields', compact('bulkEdit' ,'itemFormateur', 'groupes', 'specialites', 'users'));
        }
        return view('PkgFormation::formateur.create', compact('bulkEdit' ,'itemFormateur', 'groupes', 'specialites', 'users'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $formateur_ids = $request->input('ids', []);

        if (!is_array($formateur_ids) || count($formateur_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemFormateur = $this->formateurService->find($formateur_ids[0]);
         
 
        $specialites = $this->specialiteService->all();
        $groupes = $this->groupeService->all();
        $users = $this->userService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemFormateur = $this->formateurService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgFormation::formateur._fields', compact('bulkEdit', 'formateur_ids', 'itemFormateur', 'groupes', 'specialites', 'users'));
        }
        return view('PkgFormation::formateur.bulk-edit', compact('bulkEdit', 'formateur_ids', 'itemFormateur', 'groupes', 'specialites', 'users'));
    }
    /**
     */
    public function store(FormateurRequest $request) {
        $validatedData = $request->validated();
        $formateur = $this->formateurService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $formateur,
                'modelName' => __('PkgFormation::formateur.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $formateur->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('formateurs.edit', ['formateur' => $formateur->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $formateur,
                'modelName' => __('PkgFormation::formateur.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('formateur.show_' . $id);

        $itemFormateur = $this->formateurService->edit($id);


        $this->viewState->set('scope.chapitre.formateur_id', $id);
        

        $chapitreService =  new ChapitreService();
        $chapitres_view_data = $chapitreService->prepareDataForIndexView();
        extract($chapitres_view_data);

        $this->viewState->set('scope.commentaireRealisationTache.formateur_id', $id);
        

        $commentaireRealisationTacheService =  new CommentaireRealisationTacheService();
        $commentaireRealisationTaches_view_data = $commentaireRealisationTacheService->prepareDataForIndexView();
        extract($commentaireRealisationTaches_view_data);

        $this->viewState->set('scope.etatRealisationTache.formateur_id', $id);
        

        $etatRealisationTacheService =  new EtatRealisationTacheService();
        $etatRealisationTaches_view_data = $etatRealisationTacheService->prepareDataForIndexView();
        extract($etatRealisationTaches_view_data);

        $this->viewState->set('scope.projet.formateur_id', $id);
        

        $projetService =  new ProjetService();
        $projets_view_data = $projetService->prepareDataForIndexView();
        extract($projets_view_data);

        $this->viewState->set('scope.prioriteTache.formateur_id', $id);
        

        $prioriteTacheService =  new PrioriteTacheService();
        $prioriteTaches_view_data = $prioriteTacheService->prepareDataForIndexView();
        extract($prioriteTaches_view_data);

        if (request()->ajax()) {
            return view('PkgFormation::formateur._show', array_merge(compact('itemFormateur'),$chapitre_compact_value, $commentaireRealisationTache_compact_value, $etatRealisationTache_compact_value, $projet_compact_value, $prioriteTache_compact_value));
        }

        return view('PkgFormation::formateur.show', array_merge(compact('itemFormateur'),$chapitre_compact_value, $commentaireRealisationTache_compact_value, $etatRealisationTache_compact_value, $projet_compact_value, $prioriteTache_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('formateur.edit_' . $id);


        $itemFormateur = $this->formateurService->edit($id);


        $specialites = $this->specialiteService->all();
        $groupes = $this->groupeService->all();
        $users = $this->userService->all();


        $this->viewState->set('scope.chapitre.formateur_id', $id);
        

        $chapitreService =  new ChapitreService();
        $chapitres_view_data = $chapitreService->prepareDataForIndexView();
        extract($chapitres_view_data);

        $this->viewState->set('scope.etatRealisationTache.formateur_id', $id);
        

        $etatRealisationTacheService =  new EtatRealisationTacheService();
        $etatRealisationTaches_view_data = $etatRealisationTacheService->prepareDataForIndexView();
        extract($etatRealisationTaches_view_data);

        $this->viewState->set('scope.prioriteTache.formateur_id', $id);
        

        $prioriteTacheService =  new PrioriteTacheService();
        $prioriteTaches_view_data = $prioriteTacheService->prepareDataForIndexView();
        extract($prioriteTaches_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgFormation::formateur._edit', array_merge(compact('bulkEdit' , 'itemFormateur','groupes', 'specialites', 'users'),$chapitre_compact_value, $etatRealisationTache_compact_value, $prioriteTache_compact_value));
        }

        return view('PkgFormation::formateur.edit', array_merge(compact('bulkEdit' ,'itemFormateur','groupes', 'specialites', 'users'),$chapitre_compact_value, $etatRealisationTache_compact_value, $prioriteTache_compact_value));


    }
    /**
     */
    public function update(FormateurRequest $request, string $id) {

        $validatedData = $request->validated();
        $formateur = $this->formateurService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgFormation::formateur.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $formateur->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('formateurs.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgFormation::formateur.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $formateur_ids = $request->input('formateur_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($formateur_ids) || count($formateur_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }

        // 🔹 Récupérer les valeurs de ces champs
        $valeursChamps = [];
        foreach ($champsCoches as $field) {
            $valeursChamps[$field] = $request->input($field);
        }

        $jobManager = new JobManager();
        $jobManager->init("bulkUpdateJob",$this->service->modelName,$this->service->moduleName);
         
        dispatch(new BulkEditJob(
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $formateur_ids,
            $champsCoches,
            $valeursChamps
        ));

       
        return JsonResponseHelper::success(
             __('Mise à jour en masse effectuée avec succès.'),
                ['traitement_token' => $jobManager->getToken()]
        );

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $formateur = $this->formateurService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgFormation::formateur.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('formateurs.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgFormation::formateur.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $formateur_ids = $request->input('ids', []);
        if (!is_array($formateur_ids) || count($formateur_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($formateur_ids as $id) {
            $entity = $this->formateurService->find($id);
            $this->formateurService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($formateur_ids) . ' éléments',
            'modelName' => __('PkgFormation::formateur.plural')
        ]));
    }

    public function export($format)
    {
        $formateurs_data = $this->formateurService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new FormateurExport($formateurs_data,'csv'), 'formateur_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new FormateurExport($formateurs_data,'xlsx'), 'formateur_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new FormateurImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('formateurs.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('formateurs.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgFormation::formateur.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFormateurs()
    {
        $formateurs = $this->formateurService->all();
        return response()->json($formateurs);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (Formateur) par ID, en format JSON.
     */
    public function getFormateur(Request $request, $id)
    {
        try {
            $formateur = $this->formateurService->find($id);
            return response()->json($formateur);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Entité non trouvée ou erreur.',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    
    public function dataCalcul(Request $request)
    {
        $data = $request->all();

        // Traitement métier personnalisé (ne modifie pas la base)
        $updatedFormateur = $this->formateurService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedFormateur],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
        ));
    }
    
    public function initPassword(Request $request, string $id) {
        $formateur = $this->formateurService->initPassword($id);
        if ($request->ajax()) {
            $message = "Le mot de passe a été modifier avec succès";
            return JsonResponseHelper::success(
                $message
            );
        }
        return redirect()->route('Formateur.index')->with(
            'success',
            "Le mot de passe a été modifier avec succès"
        );
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
        $formateurRequest = new FormateurRequest();
        $fullRules = $formateurRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:formateurs,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.',null, 422);
        }
    
        $this->getService()->updateOnlyExistanteAttribute($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(
             __('Mise à jour réussie.'),
                array_merge(
                    ['entity_id' => $validated['id']],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
        );
    }
}