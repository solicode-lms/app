<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Modules\PkgCompetences\Services\TechnologyService;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgCompetences\Services\NiveauDifficulteService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgRealisationProjets\Services\ValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $this->service  =  $transfertCompetenceService;
        $this->transfertCompetenceService = $transfertCompetenceService;
        $this->technologyService = $technologyService;
        $this->competenceService = $competenceService;
        $this->niveauDifficulteService = $niveauDifficulteService;
        $this->projetService = $projetService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('transfertCompetence.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('transfertCompetence');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);


        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.transfertCompetence.projet.formateur_id') == null){
           $this->viewState->init('scope.transfertCompetence.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $transfertCompetences_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'transfertCompetences_search',
                $this->viewState->get("filter.transfertCompetence.transfertCompetences_search")
            )],
            $request->except(['transfertCompetences_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->transfertCompetenceService->prepareDataForIndexView($transfertCompetences_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgCreationProjet::transfertCompetence._index', $transfertCompetence_compact_value)->render();
            }else{
                return view($transfertCompetence_partialViewName, $transfertCompetence_compact_value)->render();
            }
        }

        return view('PkgCreationProjet::transfertCompetence.index', $transfertCompetence_compact_value);
    }
    /**
     */
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.transfertCompetence.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        $itemTransfertCompetence = $this->transfertCompetenceService->createInstance();
        

        $competences = $this->competenceService->all();
        $niveauDifficultes = $this->niveauDifficulteService->all();
        $technologies = $this->technologyService->all();
        $projets = $this->projetService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgCreationProjet::transfertCompetence._fields', compact('bulkEdit' ,'itemTransfertCompetence', 'technologies', 'competences', 'niveauDifficultes', 'projets'));
        }
        return view('PkgCreationProjet::transfertCompetence.create', compact('bulkEdit' ,'itemTransfertCompetence', 'technologies', 'competences', 'niveauDifficultes', 'projets'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $transfertCompetence_ids = $request->input('ids', []);

        if (!is_array($transfertCompetence_ids) || count($transfertCompetence_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.transfertCompetence.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
 
         $itemTransfertCompetence = $this->transfertCompetenceService->find($transfertCompetence_ids[0]);
         
 
        $competences = $this->competenceService->all();
        $niveauDifficultes = $this->niveauDifficulteService->all();
        $technologies = $this->technologyService->all();
        $projets = $this->projetService->all();

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemTransfertCompetence = $this->transfertCompetenceService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgCreationProjet::transfertCompetence._fields', compact('bulkEdit', 'transfertCompetence_ids', 'itemTransfertCompetence', 'technologies', 'competences', 'niveauDifficultes', 'projets'));
        }
        return view('PkgCreationProjet::transfertCompetence.bulk-edit', compact('bulkEdit', 'transfertCompetence_ids', 'itemTransfertCompetence', 'technologies', 'competences', 'niveauDifficultes', 'projets'));
    }
    /**
     */
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
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('transfertCompetence.show_' . $id);

        $itemTransfertCompetence = $this->transfertCompetenceService->edit($id);
        $this->authorize('view', $itemTransfertCompetence);


        $this->viewState->set('scope.validation.transfert_competence_id', $id);
        

        $validationService =  new ValidationService();
        $validations_view_data = $validationService->prepareDataForIndexView();
        extract($validations_view_data);

        if (request()->ajax()) {
            return view('PkgCreationProjet::transfertCompetence._show', array_merge(compact('itemTransfertCompetence'),$validation_compact_value));
        }

        return view('PkgCreationProjet::transfertCompetence.show', array_merge(compact('itemTransfertCompetence'),$validation_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('transfertCompetence.edit_' . $id);


        $itemTransfertCompetence = $this->transfertCompetenceService->edit($id);
        $this->authorize('edit', $itemTransfertCompetence);


        $competences = $this->competenceService->all();
        $niveauDifficultes = $this->niveauDifficulteService->all();
        $technologies = $this->technologyService->all();
        $projets = $this->projetService->all();


        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgCreationProjet::transfertCompetence._fields', array_merge(compact('bulkEdit' , 'itemTransfertCompetence','technologies', 'competences', 'niveauDifficultes', 'projets'),));
        }

        return view('PkgCreationProjet::transfertCompetence.edit', array_merge(compact('bulkEdit' ,'itemTransfertCompetence','technologies', 'competences', 'niveauDifficultes', 'projets'),));


    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $transfertCompetence_ids = $request->input('transfertCompetence_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($transfertCompetence_ids) || count($transfertCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($transfertCompetence_ids as $id) {
            $entity = $this->transfertCompetenceService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->transfertCompetenceService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->transfertCompetenceService->updateOnlyExistanteAttribute($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
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
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $transfertCompetence_ids = $request->input('ids', []);
        if (!is_array($transfertCompetence_ids) || count($transfertCompetence_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($transfertCompetence_ids as $id) {
            $entity = $this->transfertCompetenceService->find($id);
            // Vérifie si l'utilisateur peut mettre à jour l'objet 
            $transfertCompetence = $this->transfertCompetenceService->find($id);
            $this->authorize('delete', $transfertCompetence);
            $this->transfertCompetenceService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($transfertCompetence_ids) . ' éléments',
            'modelName' => __('PkgCreationProjet::transfertCompetence.plural')
        ]));
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
    


    /**
     * @DynamicPermissionIgnore
     * Met à jour les attributs, il est utilisé par type View : Widgets
     */
    public function updateAttributes(Request $request)
    {
        // Autorisation dynamique basée sur le nom du contrôleur
        $this->authorizeAction('update');
    
        $updatableFields = $this->service->getFieldsEditable();
        $transfertCompetenceRequest = new TransfertCompetenceRequest();
        $fullRules = $transfertCompetenceRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:transfert_competences,id'];
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