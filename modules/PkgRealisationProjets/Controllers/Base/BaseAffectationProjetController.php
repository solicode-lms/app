<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgRealisationProjets\Controllers\Base;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Modules\PkgFormation\Services\AnneeFormationService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgRealisationProjets\App\Requests\AffectationProjetRequest;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgRealisationProjets\App\Exports\AffectationProjetExport;
use Modules\PkgRealisationProjets\App\Imports\AffectationProjetImport;
use Modules\Core\Services\ContextState;

class BaseAffectationProjetController extends AdminController
{
    protected $affectationProjetService;
    protected $anneeFormationService;
    protected $groupeService;
    protected $projetService;

    public function __construct(AffectationProjetService $affectationProjetService, AnneeFormationService $anneeFormationService, GroupeService $groupeService, ProjetService $projetService) {
        parent::__construct();
        $this->service  =  $affectationProjetService;
        $this->affectationProjetService = $affectationProjetService;
        $this->anneeFormationService = $anneeFormationService;
        $this->groupeService = $groupeService;
        $this->projetService = $projetService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('affectationProjet.index');
        
        // ownedByUser
        if(Auth::user()->hasRole('formateur') && $this->viewState->get('scope.affectationProjet.projet.formateur_id') == null){
           $this->viewState->init('scope.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        // scopeDataByRole
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.groupe.formateurs.formateur_id'  , $this->sessionState->get('formateur_id'));
        }

         // Extraire les paramètres de recherche, pagination, filtres
        $affectationProjets_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'affectationProjets_search',
                $this->viewState->get("filter.affectationProjet.affectationProjets_search")
            )],
            $request->except(['affectationProjets_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->affectationProjetService->prepareDataForIndexView($affectationProjets_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($affectationProjet_partialViewName, $affectationProjet_compact_value)->render();
        }

        return view('PkgRealisationProjets::affectationProjet.index', $affectationProjet_compact_value);
    }
    public function create() {
        // ownedByUser
        if(Auth::user()->hasRole('formateur')){
           $this->viewState->set('scope_form.affectationProjet.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }


        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->init('scope.groupe.formateurs.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        $itemAffectationProjet = $this->affectationProjetService->createInstance();
        

        $projets = $this->projetService->all();
        $groupes = $this->groupeService->all();
        $anneeFormations = $this->anneeFormationService->all();

        if (request()->ajax()) {
            return view('PkgRealisationProjets::affectationProjet._fields', compact('itemAffectationProjet', 'anneeFormations', 'groupes', 'projets'));
        }
        return view('PkgRealisationProjets::affectationProjet.create', compact('itemAffectationProjet', 'anneeFormations', 'groupes', 'projets'));
    }
    public function store(AffectationProjetRequest $request) {
        $validatedData = $request->validated();
        $affectationProjet = $this->affectationProjetService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' => __('PkgRealisationProjets::affectationProjet.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $affectationProjet->id]
            );
        }

        return redirect()->route('affectationProjets.edit',['affectationProjet' => $affectationProjet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' => __('PkgRealisationProjets::affectationProjet.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('affectationProjet.edit_' . $id);

        if(Auth::user()->hasRole('formateur')){
            $this->viewState->set('scope.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->set('scope.groupe.formateurs.formateur_id'  , $this->sessionState->get('formateur_id'));
        }

        $itemAffectationProjet = $this->affectationProjetService->find($id);
        $this->authorize('view', $itemAffectationProjet);


        $projets = $this->projetService->all();
        $groupes = $this->groupeService->all();
        $anneeFormations = $this->anneeFormationService->all();
        

        $this->viewState->set('scope.realisationProjet.affectation_projet_id', $id);

        // scopeDataInEditContext
        $value = $itemAffectationProjet->getNestedValue('projet.formateur.id');
        $key = 'scope.etatsRealisationProjet.formateur_id';
        $this->viewState->set($key, $value);

        $realisationProjetService =  new RealisationProjetService();
        $realisationProjets_view_data = $realisationProjetService->prepareDataForIndexView();
        extract($realisationProjets_view_data);

        if (request()->ajax()) {
            return view('PkgRealisationProjets::affectationProjet._edit', array_merge(compact('itemAffectationProjet'),$anneeFormations, $groupes, $projets));
        }

        return view('PkgRealisationProjets::affectationProjet.edit', array_merge(compact('itemAffectationProjet'),$anneeFormations, $groupes, $projets));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('affectationProjet.edit_' . $id);

        if(Auth::user()->hasRole('formateur')){
            $this->viewState->set('scope.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        }
        if(Auth::user()->hasRole('formateur')){
            $this->viewState->set('scope.groupe.formateurs.formateur_id'  , $this->sessionState->get('formateur_id'));
        }

        $itemAffectationProjet = $this->affectationProjetService->find($id);
        $this->authorize('edit', $itemAffectationProjet);


        $projets = $this->projetService->all();
        $groupes = $this->groupeService->all();
        $anneeFormations = $this->anneeFormationService->all();


        $this->viewState->set('scope.realisationProjet.affectation_projet_id', $id);
        
        // scopeDataInEditContext
        $value = $itemAffectationProjet->getNestedValue('projet.formateur.id');
        $key = 'scope.etatsRealisationProjet.formateur_id';
        $this->viewState->set($key, $value);

        $realisationProjetService =  new RealisationProjetService();
        $realisationProjets_view_data = $realisationProjetService->prepareDataForIndexView();
        extract($realisationProjets_view_data);

        if (request()->ajax()) {
            return view('PkgRealisationProjets::affectationProjet._edit', array_merge(compact('itemAffectationProjet','anneeFormations', 'groupes', 'projets'),$realisationProjet_compact_value));
        }

        return view('PkgRealisationProjets::affectationProjet.edit', array_merge(compact('itemAffectationProjet','anneeFormations', 'groupes', 'projets'),$realisationProjet_compact_value));

    }
    public function update(AffectationProjetRequest $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $affectationProjet = $this->affectationProjetService->find($id);
        $this->authorize('update', $affectationProjet);

        $validatedData = $request->validated();
        $affectationProjet = $this->affectationProjetService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' =>  __('PkgRealisationProjets::affectationProjet.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $affectationProjet->id]
            );
        }

        return redirect()->route('affectationProjets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' =>  __('PkgRealisationProjets::affectationProjet.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {
        // Vérifie si l'utilisateur peut mettre à jour l'objet 
        $affectationProjet = $this->affectationProjetService->find($id);
        $this->authorize('delete', $affectationProjet);

        $affectationProjet = $this->affectationProjetService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' =>  __('PkgRealisationProjets::affectationProjet.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('affectationProjets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $affectationProjet,
                'modelName' =>  __('PkgRealisationProjets::affectationProjet.singular')
                ])
        );

    }

    public function export($format)
    {
        $affectationProjets_data = $this->affectationProjetService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new AffectationProjetExport($affectationProjets_data,'csv'), 'affectationProjet_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new AffectationProjetExport($affectationProjets_data,'xlsx'), 'affectationProjet_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new AffectationProjetImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('affectationProjets.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('affectationProjets.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgRealisationProjets::affectationProjet.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getAffectationProjets()
    {
        $affectationProjets = $this->affectationProjetService->all();
        return response()->json($affectationProjets);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $affectationProjet = $this->affectationProjetService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedAffectationProjet = $this->affectationProjetService->dataCalcul($affectationProjet);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedAffectationProjet
        ]);
    }
    

}