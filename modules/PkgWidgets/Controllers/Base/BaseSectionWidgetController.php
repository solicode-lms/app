<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Controllers\Base;
use Modules\PkgWidgets\Services\SectionWidgetService;
use Modules\Core\Services\SysColorService;
use Modules\PkgWidgets\Services\WidgetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgWidgets\App\Requests\SectionWidgetRequest;
use Modules\PkgWidgets\Models\SectionWidget;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\BulkEditJob;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgWidgets\App\Exports\SectionWidgetExport;
use Modules\PkgWidgets\App\Imports\SectionWidgetImport;
use Modules\Core\Services\ContextState;

class BaseSectionWidgetController extends AdminController
{
    protected $sectionWidgetService;
    protected $sysColorService;

    public function __construct(SectionWidgetService $sectionWidgetService, SysColorService $sysColorService) {
        parent::__construct();
        $this->service  =  $sectionWidgetService;
        $this->sectionWidgetService = $sectionWidgetService;
        $this->sysColorService = $sysColorService;
    }

    /**
     */
    public function index(Request $request) {
             
        $this->viewState->setContextKeyIfEmpty('sectionWidget.index');
        
        // userHasSentFilter doit être évalué après l'initialisation de contexteKey,
        // mais avant l'application des filtres système.
        $userHasSentFilter = $this->viewState->getFilterVariables('sectionWidget');
        $this->service->userHasSentFilter = (count($userHasSentFilter) != 0);





         // Extraire les paramètres de recherche, pagination, filtres
        $sectionWidgets_params = array_merge(
            $request->only(['page']),
            ['search' => $request->get(
                'sectionWidgets_search',
                $this->viewState->get("filter.sectionWidget.sectionWidgets_search")
            )],
            $request->except(['sectionWidgets_search', 'page'])
        );

        // prepareDataForIndexView
        $tcView = $this->sectionWidgetService->prepareDataForIndexView($sectionWidgets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            if($request['showIndex']){
                return view('PkgWidgets::sectionWidget._index', $sectionWidget_compact_value)->render();
            }else{
                return view($sectionWidget_partialViewName, $sectionWidget_compact_value)->render();
            }
        }

        return view('PkgWidgets::sectionWidget.index', $sectionWidget_compact_value);
    }
    /**
     */
    public function create() {


        $itemSectionWidget = $this->sectionWidgetService->createInstance();
        

        $sysColors = $this->sysColorService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgWidgets::sectionWidget._fields', compact('bulkEdit' ,'itemSectionWidget', 'sysColors'));
        }
        return view('PkgWidgets::sectionWidget.create', compact('bulkEdit' ,'itemSectionWidget', 'sysColors'));
    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkEditForm(Request $request) {
        $this->authorizeAction('update');

        $sectionWidget_ids = $request->input('ids', []);

        if (!is_array($sectionWidget_ids) || count($sectionWidget_ids) === 0) {
            return response()->json(['html' => '<div class="alert alert-warning">Aucun élément sélectionné.</div>']);
        }

        // Même traitement de create 

 
         $itemSectionWidget = $this->sectionWidgetService->find($sectionWidget_ids[0]);
         
 
        $sysColors = $this->sysColorService->getAllForSelect($itemSectionWidget->sysColor);

        $bulkEdit = true;

        //  Vider les valeurs : 
        $itemSectionWidget = $this->sectionWidgetService->createInstance();
        
        if (request()->ajax()) {
            return view('PkgWidgets::sectionWidget._fields', compact('bulkEdit', 'sectionWidget_ids', 'itemSectionWidget', 'sysColors'));
        }
        return view('PkgWidgets::sectionWidget.bulk-edit', compact('bulkEdit', 'sectionWidget_ids', 'itemSectionWidget', 'sysColors'));
    }
    /**
     */
    public function store(SectionWidgetRequest $request) {
        $validatedData = $request->validated();
        $sectionWidget = $this->sectionWidgetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $sectionWidget,
                'modelName' => __('PkgWidgets::sectionWidget.singular')]);
        
  
             return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $sectionWidget->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );

        }

        return redirect()->route('sectionWidgets.edit', ['sectionWidget' => $sectionWidget->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sectionWidget,
                'modelName' => __('PkgWidgets::sectionWidget.singular')
            ])
        );
    }
    /**
     */
    public function show(string $id) {

        $this->viewState->setContextKey('sectionWidget.show_' . $id);

        $itemSectionWidget = $this->sectionWidgetService->edit($id);


        $this->viewState->set('scope.widget.section_widget_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        if (request()->ajax()) {
            return view('PkgWidgets::sectionWidget._show', array_merge(compact('itemSectionWidget'),$widget_compact_value));
        }

        return view('PkgWidgets::sectionWidget.show', array_merge(compact('itemSectionWidget'),$widget_compact_value));

    }
    /**
     */
    public function edit(string $id) {

        $this->viewState->setContextKey('sectionWidget.edit_' . $id);


        $itemSectionWidget = $this->sectionWidgetService->edit($id);


        $sysColors = $this->sysColorService->getAllForSelect($itemSectionWidget->sysColor);


        $this->viewState->set('scope.widget.section_widget_id', $id);
        

        $widgetService =  new WidgetService();
        $widgets_view_data = $widgetService->prepareDataForIndexView();
        extract($widgets_view_data);

        $bulkEdit = false;

        if (request()->ajax()) {
            return view('PkgWidgets::sectionWidget._edit', array_merge(compact('bulkEdit' , 'itemSectionWidget','sysColors'),$widget_compact_value));
        }

        return view('PkgWidgets::sectionWidget.edit', array_merge(compact('bulkEdit' ,'itemSectionWidget','sysColors'),$widget_compact_value));


    }
    /**
     */
    public function update(SectionWidgetRequest $request, string $id) {

        $validatedData = $request->validated();
        $sectionWidget = $this->sectionWidgetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $sectionWidget,
                'modelName' =>  __('PkgWidgets::sectionWidget.singular')]);
            
            return JsonResponseHelper::success(
             $message,
                array_merge(
                    ['entity_id' => $sectionWidget->id],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
                )
            );
        }

        return redirect()->route('sectionWidgets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sectionWidget,
                'modelName' =>  __('PkgWidgets::sectionWidget.singular')
                ])
        );

    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkUpdate(Request $request) {
        $this->authorizeAction('update');

        // 1) Structure de la requête (ids + champs cochés)
        $request->validate([
            'sectionWidget_ids'   => ['required', 'array', 'min:1'],
            'fields_modifiables'               => ['required', 'array', 'min:1']
        ]);

        $ids          = $request->input('sectionWidget_ids', []);
        $champsCoches = $request->input('fields_modifiables', []);

        // 2) Restreindre aux champs réellement éditables (côté service/UI)
        $updatableFields = $this->service->getFieldsEditable();
        $requestedFields = array_values(array_intersect($champsCoches, $updatableFields));
        if (empty($requestedFields)) {
            return JsonResponseHelper::error("Aucun champ sélectionné valide.");
        }

        // 3) Valeurs “bulk” proposées par l'utilisateur (payload uniforme)
        $valeursChamps = [];
        foreach ($requestedFields as $field) {
            $valeursChamps[$field] = $request->input($field);
        }

        // 4) Charger rules/messages du FormRequest sans dépendre de la current request
        $form         = new \Modules\PkgWidgets\App\Requests\SectionWidgetRequest();
        $fullRules    = $form->rules();
        $fullMessages = method_exists($form, 'messages') ? $form->messages() : [];

        // 5) Autorisation & sanitation par rôles pour CHAQUE ID
        //    -> on intersecte les champs réellement autorisés (via sanitizePayloadByRoles)
        $allowedAcrossAll = $requestedFields;
        foreach ($ids as $id) {
            $model = $this->sectionWidgetService->find($id);
            $this->authorize('update', $model);

            // sanitizePayloadByRoles complète les champs non autorisés avec la valeur du modèle
            // et nous retourne la liste des champs "kept" donc effectivement modifiables par cet utilisateur
            [, $kept /* $removed */] = $this->service->sanitizePayloadByRoles(
                $valeursChamps,
                $model,
                $request->user()
            );

            $allowedAcrossAll = array_values(array_intersect($allowedAcrossAll, $kept));
            if (empty($allowedAcrossAll)) {
                break;
            }
        }

        if (empty($allowedAcrossAll)) {
            return JsonResponseHelper::error("Aucun des champs sélectionnés n’est autorisé à être modifié pour les éléments choisis.");
        }

        // 6) Payload & Rules finaux (uniquement champs autorisés pour TOUS les IDs)
        $finalPayload = [];
        foreach ($allowedAcrossAll as $f) {
            $finalPayload[$f] = $valeursChamps[$f] ?? null;
        }

        // Normaliser '' -> null pour les champs "nullable" en se basant sur les valeurs bulk
        foreach ($allowedAcrossAll as $f) {
            $rule = $fullRules[$f] ?? null;
            if (is_string($rule) && str_contains($rule, 'nullable')) {
                if (array_key_exists($f, $valeursChamps) && $valeursChamps[$f] === '') {
                    $finalPayload[$f] = null;
                }
            }
        }

        $finalRules = array_intersect_key($fullRules, array_flip($allowedAcrossAll));

        // 7) Validation finale avec les rules/messages du FormRequest
        \Illuminate\Support\Facades\Validator::make($finalPayload, $finalRules, $fullMessages)->validate();

        // 8) Dispatch du job avec uniquement les champs autorisés
        $jobManager = new JobManager();
        $jobManager->init("bulkUpdateJob", $this->service->modelName, $this->service->moduleName);

        $ignored = array_values(array_diff($requestedFields, $allowedAcrossAll));

        dispatch(new BulkEditJob(
            Auth::id(),
            ucfirst($this->service->moduleName),
            ucfirst($this->service->modelName),
            "bulkUpdateJob",
            $jobManager->getToken(),
            $ids,
            $allowedAcrossAll,
            $finalPayload
        ));

        $msg = 'Mise à jour en masse effectuée avec succès.';
        if (!empty($ignored)) {
            $msg .= ' Champs ignorés (non autorisés) : ' . implode(', ', $ignored) . '.';
        }

        return JsonResponseHelper::success($msg, [
            'traitement_token' => $jobManager->getToken()
        ]);
    
    }
    /**
     */
    public function destroy(Request $request, string $id) {

        $sectionWidget = $this->sectionWidgetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $sectionWidget,
                'modelName' =>  __('PkgWidgets::sectionWidget.singular')]);
            

            return JsonResponseHelper::success(
                $message,
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            );
        }

        return redirect()->route('sectionWidgets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sectionWidget,
                'modelName' =>  __('PkgWidgets::sectionWidget.singular')
                ])
        );


    }
    /**
     * @DynamicPermissionIgnore
     */
    public function bulkDelete(Request $request) {
        $this->authorizeAction('destroy');
        $sectionWidget_ids = $request->input('ids', []);
        if (!is_array($sectionWidget_ids) || count($sectionWidget_ids) === 0) {
            return JsonResponseHelper::error("Aucun élément sélectionné.");
        }
        foreach ($sectionWidget_ids as $id) {
            $entity = $this->sectionWidgetService->find($id);
            $this->sectionWidgetService->destroy($id);
        }
        return JsonResponseHelper::success(__('Core::msg.deleteSuccess', [
            'entityToString' => count($sectionWidget_ids) . ' éléments',
            'modelName' => __('PkgWidgets::sectionWidget.plural')
        ]));
    }

    public function export($format)
    {
        $sectionWidgets_data = $this->sectionWidgetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new SectionWidgetExport($sectionWidgets_data,'csv'), 'sectionWidget_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new SectionWidgetExport($sectionWidgets_data,'xlsx'), 'sectionWidget_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new SectionWidgetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sectionWidgets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sectionWidgets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgWidgets::sectionWidget.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSectionWidgets()
    {
        $sectionWidgets = $this->sectionWidgetService->all();
        return response()->json($sectionWidgets);
    }

    /**
     * @DynamicPermissionIgnore
     * Retourne une tâche (SectionWidget) par ID, en format JSON.
     */
    public function getSectionWidget(Request $request, $id)
    {
        try {
            $sectionWidget = $this->sectionWidgetService->find($id);
            return response()->json($sectionWidget);
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
        $updatedSectionWidget = $this->sectionWidgetService->dataCalcul($data);

        return response()->json(  array_merge(
                   ['success' => true,'entity' => $updatedSectionWidget],
                    $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
        ));
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
        $sectionWidgetRequest = new SectionWidgetRequest();
        $fullRules = $sectionWidgetRequest->rules();
        $rules = collect($fullRules)
            ->only(array_intersect(array_keys($request->all()), $updatableFields))
            ->toArray();

        // Ajout obligatoire de l'ID
        $rules['id'] = ['required', 'integer', 'exists:section_widgets,id'];
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

    /**
     * Retourne les métadonnées d’un champ (type, options, validation, etag…)
     *  @DynamicPermissionIgnore
     */
    public function fieldMeta(int $id, string $field)
    {
        // $this->authorizeAction('update');
        $itemSectionWidget = SectionWidget::findOrFail($id);


        $data = $this->service->buildFieldMeta($itemSectionWidget, $field);
        return response()->json(
            $data
        );
    }

    /**
     * PATCH inline d’une cellule avec gestion de l’ETag
     * @DynamicPermissionIgnore
     */
    public function patchInline(Request $request, int $id)
    {

        $this->authorizeAction('update');
        $itemSectionWidget = SectionWidget::findOrFail($id);


        // Vérification ETag
        $ifMatch = $request->header('If-Match');
        $etag = $this->service->etag($itemSectionWidget);
        if ($ifMatch && $ifMatch !== $etag) {
            return response()->json(['error' => 'conflict'], 409);
        }

        // Appliquer le patch
        $changes = $request->input('changes', []);
        $updated = $this->service->applyInlinePatch($itemSectionWidget, $changes);

        return response()->json(
            array_merge(
                [
                    "ok"        => true,
                    "entity_id" => $updated->id,
                    "display"   => $this->service->formatDisplayValues($updated, array_keys($changes)),
                    "etag"      => $this->service->etag($updated),
                ],
                $this->service->getCrudJobToken() ? ['traitement_token' => $this->service->getCrudJobToken()] : []
            )
        );
    }

   
}