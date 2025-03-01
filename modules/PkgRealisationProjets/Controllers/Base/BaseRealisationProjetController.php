<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService;
use Modules\PkgRealisationProjets\Services\LivrablesRealisationService;
use Modules\PkgRealisationProjets\Services\ValidationService;
use Modules\PkgGestionTaches\Services\RealisationTacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationProjets\App\Requests\RealisationProjetRequest;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\RealisationProjetExport;
use Modules\PkgRealisationProjets\App\Imports\RealisationProjetImport;
use Modules\Core\Services\ContextState;

class BaseRealisationProjetController extends AdminController
{
    protected $realisationProjetService;
    protected $affectationProjetService;
    protected $apprenantService;
    protected $etatsRealisationProjetService;

    public function __construct(RealisationProjetService $realisationProjetService, AffectationProjetService $affectationProjetService, ApprenantService $apprenantService, EtatsRealisationProjetService $etatsRealisationProjetService) {
        parent::__construct();
        $this->realisationProjetService = $realisationProjetService;
        $this->affectationProjetService = $affectationProjetService;
        $this->apprenantService = $apprenantService;
        $this->etatsRealisationProjetService = $etatsRealisationProjetService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('realisationProjet.index');
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('filter.realisationProjet.affectationProjet.projet.formateur_id') == null){
           $this->viewState->init('filter.realisationProjet.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant') && $this->viewState->get('filter.realisationProjet.apprenant_id') == null){
           $this->viewState->init('filter.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }


        // Extraire les paramètres de recherche, page, et filtres
        $realisationProjets_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('realisationProjets_search', $this->viewState->get("filter.realisationProjet.realisationProjets_search"))],
            $request->except(['realisationProjets_search', 'page', 'sort'])
        );

        // Paginer les realisationProjets
        $realisationProjets_data = $this->realisationProjetService->paginate($realisationProjets_params);

        // Récupérer les statistiques et les champs filtrables
        $realisationProjets_stats = $this->realisationProjetService->getrealisationProjetStats();
        $this->viewState->set('stats.realisationProjet.stats'  , $realisationProjets_stats);
        $realisationProjets_filters = $this->realisationProjetService->getFieldsFilterable();
        $realisationProjet_instance =  $this->realisationProjetService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgRealisationProjets::realisationProjet._table', compact('realisationProjets_data', 'realisationProjets_stats', 'realisationProjets_filters','realisationProjet_instance'))->render();
        }

        return view('PkgRealisationProjets::realisationProjet.index', compact('realisationProjets_data', 'realisationProjets_stats', 'realisationProjets_filters','realisationProjet_instance'));
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.realisationProjet.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('apprenant')){
           $this->viewState->set('scope_form.realisationProjet.apprenant_id'  , $this->sessionState->get('apprenant_id'));
        }


        $itemRealisationProjet = $this->realisationProjetService->createInstance();
        
        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.formateur.id');
        $key = 'scope.etatsRealisationProjet.formateur_id';
        $this->viewState->set($key, $value);

        $affectationProjets = $this->affectationProjetService->all();
        $apprenants = $this->apprenantService->all();
        $etatsRealisationProjets = $this->etatsRealisationProjetService->all();

        if (request()->ajax()) {
            return view('PkgRealisationProjets::realisationProjet._fields', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets'));
        }
        return view('PkgRealisationProjets::realisationProjet.create', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets'));
    }
    public function store(RealisationProjetRequest $request) {
        $validatedData = $request->validated();
        $realisationProjet = $this->realisationProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' => __('PkgRealisationProjets::realisationProjet.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $realisationProjet->id]
            );
        }

        return redirect()->route('realisationProjets.edit',['realisationProjet' => $realisationProjet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' => __('PkgRealisationProjets::realisationProjet.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('realisationProjet.edit_' . $id);


        $itemRealisationProjet = $this->realisationProjetService->find($id);
        $this->authorize('view', $itemRealisationProjet);

        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.formateur.id');
        $key = 'scope.etatsRealisationProjet.formateur_id';
        $this->viewState->set($key, $value);

        $affectationProjets = $this->affectationProjetService->all();
        $apprenants = $this->apprenantService->all();
        $etatsRealisationProjets = $this->etatsRealisationProjetService->all();


        $this->viewState->set('scope.livrablesRealisation.realisation_projet_id', $id);

        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.id');
        $key = 'scope.livrable.projet_id';
        $this->viewState->set($key, $value);

        $livrablesRealisationService =  new LivrablesRealisationService();
        $livrablesRealisations_data =  $itemRealisationProjet->livrablesRealisations()->paginate(10);
        $livrablesRealisations_stats = $livrablesRealisationService->getlivrablesRealisationStats();
        $livrablesRealisations_filters = $livrablesRealisationService->getFieldsFilterable();
        $livrablesRealisation_instance =  $livrablesRealisationService->createInstance();

        $this->viewState->set('scope.validation.realisation_projet_id', $id);

        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.id');
        $key = 'scope.transfertCompetence.projet_id';
        $this->viewState->set($key, $value);

        $validationService =  new ValidationService();
        $validations_data =  $itemRealisationProjet->validations()->paginate(10);
        $validations_stats = $validationService->getvalidationStats();
        $validations_filters = $validationService->getFieldsFilterable();
        $validation_instance =  $validationService->createInstance();

        $this->viewState->set('scope.realisationTache.realisation_projet_id', $id);


        $realisationTacheService =  new RealisationTacheService();
        $realisationTaches_data =  $itemRealisationProjet->realisationTaches()->paginate(10);
        $realisationTaches_stats = $realisationTacheService->getrealisationTacheStats();
        $realisationTaches_filters = $realisationTacheService->getFieldsFilterable();
        $realisationTache_instance =  $realisationTacheService->createInstance();

        if (request()->ajax()) {
            return view('PkgRealisationProjets::realisationProjet._edit', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets', 'livrablesRealisations_data', 'validations_data', 'realisationTaches_data', 'livrablesRealisations_stats', 'validations_stats', 'realisationTaches_stats', 'livrablesRealisations_filters', 'validations_filters', 'realisationTaches_filters', 'livrablesRealisation_instance', 'validation_instance', 'realisationTache_instance'));
        }

        return view('PkgRealisationProjets::realisationProjet.edit', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets', 'livrablesRealisations_data', 'validations_data', 'realisationTaches_data', 'livrablesRealisations_stats', 'validations_stats', 'realisationTaches_stats', 'livrablesRealisations_filters', 'validations_filters', 'realisationTaches_filters', 'livrablesRealisation_instance', 'validation_instance', 'realisationTache_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('realisationProjet.edit_' . $id);


        $itemRealisationProjet = $this->realisationProjetService->find($id);
        $this->authorize('edit', $itemRealisationProjet);

        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.formateur.id');
        $key = 'scope.etatsRealisationProjet.formateur_id';
        $this->viewState->set($key, $value);

        $affectationProjets = $this->affectationProjetService->all();
        $apprenants = $this->apprenantService->all();
        $etatsRealisationProjets = $this->etatsRealisationProjetService->all();


        $this->viewState->set('scope.livrablesRealisation.realisation_projet_id', $id);
        
        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.id');
        $key = 'scope.livrable.projet_id';
        $this->viewState->set($key, $value);

        $livrablesRealisationService =  new LivrablesRealisationService();
        $livrablesRealisations_data =  $itemRealisationProjet->livrablesRealisations()->paginate(10);
        $livrablesRealisations_stats = $livrablesRealisationService->getlivrablesRealisationStats();
        $this->viewState->set('stats.livrablesRealisation.stats'  , $livrablesRealisations_stats);
        $livrablesRealisations_filters = $livrablesRealisationService->getFieldsFilterable();
        $livrablesRealisation_instance =  $livrablesRealisationService->createInstance();

        $this->viewState->set('scope.validation.realisation_projet_id', $id);
        
        // scopeDataInEditContext
        $value = $itemRealisationProjet->getNestedValue('affectationProjet.projet.id');
        $key = 'scope.transfertCompetence.projet_id';
        $this->viewState->set($key, $value);

        $validationService =  new ValidationService();
        $validations_data =  $itemRealisationProjet->validations()->paginate(10);
        $validations_stats = $validationService->getvalidationStats();
        $this->viewState->set('stats.validation.stats'  , $validations_stats);
        $validations_filters = $validationService->getFieldsFilterable();
        $validation_instance =  $validationService->createInstance();

        $this->viewState->set('scope.realisationTache.realisation_projet_id', $id);
        

        $realisationTacheService =  new RealisationTacheService();
        $realisationTaches_data =  $itemRealisationProjet->realisationTaches()->paginate(10);
        $realisationTaches_stats = $realisationTacheService->getrealisationTacheStats();
        $this->viewState->set('stats.realisationTache.stats'  , $realisationTaches_stats);
        $realisationTaches_filters = $realisationTacheService->getFieldsFilterable();
        $realisationTache_instance =  $realisationTacheService->createInstance();

        if (request()->ajax()) {
            return view('PkgRealisationProjets::realisationProjet._edit', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets', 'livrablesRealisations_data', 'validations_data', 'realisationTaches_data', 'livrablesRealisations_stats', 'validations_stats', 'realisationTaches_stats', 'livrablesRealisations_filters', 'validations_filters', 'realisationTaches_filters', 'livrablesRealisation_instance', 'validation_instance', 'realisationTache_instance'));
        }

        return view('PkgRealisationProjets::realisationProjet.edit', compact('itemRealisationProjet', 'affectationProjets', 'apprenants', 'etatsRealisationProjets', 'livrablesRealisations_data', 'validations_data', 'realisationTaches_data', 'livrablesRealisations_stats', 'validations_stats', 'realisationTaches_stats', 'livrablesRealisations_filters', 'validations_filters', 'realisationTaches_filters', 'livrablesRealisation_instance', 'validation_instance', 'realisationTache_instance'));

    }
    public function update(RealisationProjetRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $realisationProjet = $this->realisationProjetService->find($id);
        $this->authorize('update', $realisationProjet);

        $validatedData = $request->validated();
        $realisationProjet = $this->realisationProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' =>  __('PkgRealisationProjets::realisationProjet.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $realisationProjet->id]
            );
        }

        return redirect()->route('realisationProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' =>  __('PkgRealisationProjets::realisationProjet.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $realisationProjet = $this->realisationProjetService->find($id);
        $this->authorize('delete', $realisationProjet);

        $realisationProjet = $this->realisationProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' =>  __('PkgRealisationProjets::realisationProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('realisationProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $realisationProjet,
                'modelName' =>  __('PkgRealisationProjets::realisationProjet.singular')
                ])
        );

    }

    public function export($format)
    {
        $realisationProjets_data = $this->realisationProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationProjetExport($realisationProjets_data,'csv'), 'realisationProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new RealisationProjetExport($realisationProjets_data,'xlsx'), 'realisationProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new RealisationProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('realisationProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('realisationProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationProjets::realisationProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getRealisationProjets()
    {
        $realisationProjets = $this->realisationProjetService->all();
        return response()->json($realisationProjets);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $realisationProjet = $this->realisationProjetService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedRealisationProjet = $this->realisationProjetService->dataCalcul($realisationProjet);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedRealisationProjet
        ]);
    }
    

}
