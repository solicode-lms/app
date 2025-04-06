<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\ValidationService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationProjets\App\Requests\ValidationRequest;
use Modules\PkgRealisationProjets\Models\Validation;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\ValidationExport;
use Modules\PkgRealisationProjets\App\Imports\ValidationImport;
use Modules\Core\Services\ContextState;

class BaseValidationController extends AdminController
{
    protected $validationService;
    protected $realisationProjetService;
    protected $transfertCompetenceService;

    public function __construct(ValidationService $validationService, RealisationProjetService $realisationProjetService, TransfertCompetenceService $transfertCompetenceService) {
        parent::__construct();
        $this->service  =  $validationService;
        $this->validationService = $validationService;
        $this->realisationProjetService = $realisationProjetService;
        $this->transfertCompetenceService = $transfertCompetenceService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('validation.index');
        
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.validation.realisationProjet.affectationProjet.projet.formateur_id') == null){
           $this->viewState->init('filter.validation.realisationProjet.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('filter.validation.realisationProjet.apprenant_id') == null){
           $this->viewState->init('filter.validation.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }



         // Extraire les paramètres de recherche, pagination, filtres
        $validations_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'validations_search',
                $this->viewState->get("filter.validation.validations_search")
            )],
            $request->except(['validations_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->validationService->prepareDataForIndexView($validations_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($validation_partialViewName, $validation_compact_value)->render();
        }

        return view('PkgRealisationProjets::validation.index', $validation_compact_value);
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.validation.realisationProjet.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.validation.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }


        $itemValidation = $this->validationService->createInstance();
        

        $transfertCompetences = $this->transfertCompetenceService->all();
        $realisationProjets = $this->realisationProjetService->all();

        if (request()->ajax()) {
            return view('PkgRealisationProjets::validation._fields', compact('itemValidation', 'realisationProjets', 'transfertCompetences'));
        }
        return view('PkgRealisationProjets::validation.create', compact('itemValidation', 'realisationProjets', 'transfertCompetences'));
    }
    public function store(ValidationRequest $request) {
        $validatedData = $request->validated();
        $validation = $this->validationService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $validation,
                'modelName' => __('PkgRealisationProjets::validation.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $validation->id]
            );
        }

        return redirect()->route('validations.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $validation,
                'modelName' => __('PkgRealisationProjets::validation.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('validation.edit_' . $id);


        $itemValidation = $this->validationService->find($id);
        $this->authorize('view', $itemValidation);


        $transfertCompetences = $this->transfertCompetenceService->all();
        $realisationProjets = $this->realisationProjetService->all();
        

        if (request()->ajax()) {
            return view('PkgRealisationProjets::validation._fields', array_merge(compact('itemValidation'),$realisationProjets, $transfertCompetences));
        }

        return view('PkgRealisationProjets::validation.edit', array_merge(compact('itemValidation'),$realisationProjets, $transfertCompetences));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('validation.edit_' . $id);


        $itemValidation = $this->validationService->find($id);
        $this->authorize('edit', $itemValidation);


        $transfertCompetences = $this->transfertCompetenceService->all();
        $realisationProjets = $this->realisationProjetService->all();


        if (request()->ajax()) {
            return view('PkgRealisationProjets::validation._fields', array_merge(compact('itemValidation','realisationProjets', 'transfertCompetences'),));
        }

        return view('PkgRealisationProjets::validation.edit', array_merge(compact('itemValidation','realisationProjets', 'transfertCompetences'),));

    }
    public function update(ValidationRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $validation = $this->validationService->find($id);
        $this->authorize('update', $validation);

        $validatedData = $request->validated();
        $validation = $this->validationService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $validation,
                'modelName' =>  __('PkgRealisationProjets::validation.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $validation->id]
            );
        }

        return redirect()->route('validations.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $validation,
                'modelName' =>  __('PkgRealisationProjets::validation.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $validation = $this->validationService->find($id);
        $this->authorize('delete', $validation);

        $validation = $this->validationService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $validation,
                'modelName' =>  __('PkgRealisationProjets::validation.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('validations.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $validation,
                'modelName' =>  __('PkgRealisationProjets::validation.singular')
                ])
        );

    }

    public function export($format)
    {
        $validations_data = $this->validationService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new ValidationExport($validations_data,'csv'), 'validation_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new ValidationExport($validations_data,'xlsx'), 'validation_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new ValidationImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('validations.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('validations.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationProjets::validation.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getValidations()
    {
        $validations = $this->validationService->all();
        return response()->json($validations);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $validation = $this->validationService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedValidation = $this->validationService->dataCalcul($validation);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedValidation
        ]);
    }
    

}