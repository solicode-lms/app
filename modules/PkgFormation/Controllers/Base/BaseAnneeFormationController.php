<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\AnneeFormationService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgApprenants\Services\GroupeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgFormation\App\Requests\AnneeFormationRequest;
use Modules\PkgFormation\Models\AnneeFormation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgFormation\App\Exports\AnneeFormationExport;
use Modules\PkgFormation\App\Imports\AnneeFormationImport;
use Modules\Core\Services\ContextState;

class BaseAnneeFormationController extends AdminController
{
    protected $anneeFormationService;

    public function __construct(AnneeFormationService $anneeFormationService) {
        parent::__construct();
        $this->service  =  $anneeFormationService;
        $this->anneeFormationService = $anneeFormationService;
    }

    /**
     */
    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('anneeFormation.index');
        
        $userHasSentFilter = $this->viewState->getFilterVariables('anneeFormation');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $anneeFormations_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'anneeFormations_search',
                $this->viewState->get("filter.anneeFormation.anneeFormations_search")
            )],
            $request->except(['anneeFormations_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->anneeFormationService->prepareDataForIndexView($anneeFormations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgFormation::anneeFormation._index', $anneeFormation_compact_value)->render();
            }else{
                return view($anneeFormation_partialViewName, $anneeFormation_compact_value)->render();
            }
        }

        return view('PkgFormation::anneeFormation.index', $anneeFormation_compact_value);
    }
    /**
     */
    public function create() {


        $itemAnneeFormation = $this->anneeFormationService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._fields', compact('itemAnneeFormation'));
        }
        return view('PkgFormation::anneeFormation.create', compact('itemAnneeFormation'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $anneeFormation_ids = $request->input('ids', []);

        if (!is_array($anneeFormation_ids) || count($anneeFormation_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemAnneeFormation = $this->anneeFormationService->find($anneeFormation_ids[0]);
         
 

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemAnneeFormation = $this->anneeFormationService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._fields', compact('bulkEdit', 'anneeFormation_ids', 'itemAnneeFormation'));
        }
        return view('PkgFormation::anneeFormation.bulk-edit', compact('bulkEdit', 'anneeFormation_ids', 'itemAnneeFormation'));
    }
    /**
     */
    public function store(AnneeFormationRequest $request) {
        $validatedData = $request->validated();
        $anneeFormation = $this->anneeFormationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' => __('PkgFormation::anneeFormation.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $anneeFormation->id]
            );
        }

        return redirect()->route('anneeFormations.edit',['anneeFormation' => $anneeFormation->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' => __('PkgFormation::anneeFormation.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('anneeFormation.edit_' . $id);


        $itemAnneeFormation = $this->anneeFormationService->edit($id);




        $this->viewState->set('scope.affectationProjet.annee_formation_id', $id);
        

        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_view_data = $affectationProjetService->prepareDataForIndexView();
        extract($affectationProjets_view_data);

        $this->viewState->set('scope.groupe.annee_formation_id', $id);
        

        $groupeService =  new GroupeService();
        $groupes_view_data = $groupeService->prepareDataForIndexView();
        extract($groupes_view_data);

        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._edit', array_merge(compact('itemAnneeFormation',),$affectationProjet_compact_value, $groupe_compact_value));
        }

        return view('PkgFormation::anneeFormation.edit', array_merge(compact('itemAnneeFormation',),$affectationProjet_compact_value, $groupe_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('anneeFormation.edit_' . $id);


        $itemAnneeFormation = $this->anneeFormationService->edit($id);




        $this->viewState->set('scope.affectationProjet.annee_formation_id', $id);
        

        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_view_data = $affectationProjetService->prepareDataForIndexView();
        extract($affectationProjets_view_data);

        $this->viewState->set('scope.groupe.annee_formation_id', $id);
        

        $groupeService =  new GroupeService();
        $groupes_view_data = $groupeService->prepareDataForIndexView();
        extract($groupes_view_data);

        if (request()->ajax()) {
            return view('PkgFormation::anneeFormation._edit', array_merge(compact('itemAnneeFormation',),$affectationProjet_compact_value, $groupe_compact_value));
        }

        return view('PkgFormation::anneeFormation.edit', array_merge(compact('itemAnneeFormation',),$affectationProjet_compact_value, $groupe_compact_value));


    }
    /**
     */
    public function update(AnneeFormationRequest $request, string $id) {

        $validatedData = $request->validated();
        $anneeFormation = $this->anneeFormationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $anneeFormation->id]
            );
        }

        return redirect()->route('anneeFormations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');
    
        $anneeFormation_ids = $request->input('anneeFormation_ids', []);
        $champsCoches = $request->input('fields_modifiables', []); // ✅ champs à appliquer
    
        if (!is_array($anneeFormation_ids) || count($anneeFormation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        if (empty($champsCoches)) {
            return JsonResponseHelper::error("Aucun champ sélectionné pour la mise à jour.");
        }
    
        foreach ($anneeFormation_ids as $id) {
            $entity = $this->anneeFormationService->find($id);
            $this->authorize('update', $entity);
    
            $allFields = $this->anneeFormationService->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $request->input($field)])
                ->toArray();
    
            if (!empty($data)) {
                $this->anneeFormationService->update($id, $data);
            }
        }
    
        return JsonResponseHelper::success(__('Mise à jour en masse effectuée avec succès.'));

    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $anneeFormation = $this->anneeFormationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('anneeFormations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $anneeFormation,
                'modelName' =>  __('PkgFormation::anneeFormation.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $anneeFormation_ids = $request->input('ids', []);
        if (!is_array($anneeFormation_ids) || count($anneeFormation_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($anneeFormation_ids as $id) {
            $entity = $this->anneeFormationService->find($id);
            $this->anneeFormationService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($anneeFormation_ids) . ' éléments',
            'modelName' => __('PkgFormation::anneeFormation.plural')
        ]));
    }

    public function export($format)
    {
        $anneeFormations_data = $this->anneeFormationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new AnneeFormationExport($anneeFormations_data,'csv'), 'anneeFormation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new AnneeFormationExport($anneeFormations_data,'xlsx'), 'anneeFormation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new AnneeFormationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('anneeFormations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('anneeFormations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgFormation::anneeFormation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getAnneeFormations()
    {
        $anneeFormations = $this->anneeFormationService->all();
        return response()->json($anneeFormations);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $anneeFormation = $this->anneeFormationService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedAnneeFormation = $this->anneeFormationService->dataCalcul($anneeFormation);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedAnneeFormation
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
        $anneeFormationRequest = new AnneeFormationRequest();
        $fullRules = $anneeFormationRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:annee_formations,id'];
        $validated = $request->validate($rules);

        
        $dataToUpdate = collect($validated)->only($updatableFields)->toArray();
    
        if (empty($dataToUpdate)) {
            return JsonResponseHelper::error('Aucune donnée à mettre à jour.', 422);
        }
    
        $this->getService()->update($validated['id'], $dataToUpdate);
    
        return JsonResponseHelper::success(__('Mise à jour réussie.'), [
            'entity_id' => $validated['id']
        ]);
    }
}