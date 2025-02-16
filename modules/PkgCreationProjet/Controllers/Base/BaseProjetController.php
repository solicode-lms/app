<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Modules\PkgCreationProjet\Services\LivrableService;
use Modules\PkgCreationProjet\Services\ResourceService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Illuminate\Http\Request;
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
    protected $formateurService;

    public function __construct(ProjetService $projetService, FormateurService $formateurService) {
        parent::__construct();
        $this->projetService = $projetService;
        $this->formateurService = $formateurService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('projet.index');
        $this->viewState->init('filter.projet.formateur_id'  , $this->sessionState->get('formateur_id'));

        // Extraire les paramètres de recherche, page, et filtres
        $projets_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('projets_search', $this->viewState->get("filter.projet.projets_search"))],
            $request->except(['projets_search', 'page', 'sort'])
        );

        // Paginer les projets
        $projets_data = $this->projetService->paginate($projets_params);

        // Récupérer les statistiques et les champs filtrables
        $projets_stats = $this->projetService->getprojetStats();
        $projets_filters = $this->projetService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgCreationProjet::projet._table', compact('projets_data', 'projets_stats', 'projets_filters'))->render();
        }

        return view('PkgCreationProjet::projet.index', compact('projets_data', 'projets_stats', 'projets_filters'));
    }
    public function create() {
        $this->viewState->set('scope_form.projet.formateur_id'  , $this->sessionState->get('formateur_id'));
        $itemProjet = $this->projetService->createInstance();
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._fields', compact('itemProjet', 'formateurs'));
        }
        return view('PkgCreationProjet::projet.create', compact('itemProjet', 'formateurs'));
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
        return $this->edit( $id);
    }
    public function edit(string $id) {

        $this->viewState->setContextKey('projet.edit_' . $id);

        $itemProjet = $this->projetService->find($id);
        $formateurs = $this->formateurService->all();

        $this->viewState->set('scope.transfertCompetence.projet_id', $id);
        $transfertCompetenceService =  new TransfertCompetenceService();
        $transfertCompetences_data =  $itemProjet->transfertCompetences()->paginate(10);
        $transfertCompetences_stats = $transfertCompetenceService->gettransfertCompetenceStats();
        $transfertCompetences_filters = $transfertCompetenceService->getFieldsFilterable();
        $transfertCompetence_instance =  $transfertCompetenceService->createInstance();

        $this->viewState->set('scope.livrable.projet_id', $id);
        $livrableService =  new LivrableService();
        $livrables_data =  $itemProjet->livrables()->paginate(10);
        $livrables_stats = $livrableService->getlivrableStats();
        $livrables_filters = $livrableService->getFieldsFilterable();
        
        $this->viewState->set('scope.resource.projet_id', $id);
        $resourceService =  new ResourceService();
        $resources_data =  $itemProjet->resources()->paginate(10);
        $resources_stats = $resourceService->getresourceStats();
        $resources_filters = $resourceService->getFieldsFilterable();
        
        $this->viewState->set('scope.affectationProjet.projet_id', $id);
        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_data =  $itemProjet->affectationProjets()->paginate(10);
        $affectationProjets_stats = $affectationProjetService->getaffectationProjetStats();
        $affectationProjets_filters = $affectationProjetService->getFieldsFilterable();
        

        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._edit', compact('itemProjet', 'formateurs', 'transfertCompetences_data', 'livrables_data', 'resources_data', 'affectationProjets_data', 'transfertCompetences_stats', 'livrables_stats', 'resources_stats', 'affectationProjets_stats', 'transfertCompetences_filters', 'livrables_filters', 'resources_filters', 'affectationProjets_filters','transfertCompetence_instance'));
        }

        return view('PkgCreationProjet::projet.edit', compact('itemProjet', 'formateurs', 'transfertCompetences_data', 'livrables_data', 'resources_data', 'affectationProjets_data', 'transfertCompetences_stats', 'livrables_stats', 'resources_stats', 'affectationProjets_stats', 'transfertCompetences_filters', 'livrables_filters', 'resources_filters', 'affectationProjets_filters','transfertCompetence_instance'));

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
