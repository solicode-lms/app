<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgApprenants\Services\ApprenantService;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgFormation\Services\AnneeFormationService;
use Modules\PkgFormation\Services\FiliereService;
use Modules\PkgRealisationProjets\Services\AffectationProjetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprenants\App\Requests\GroupeRequest;
use Modules\PkgApprenants\Models\Groupe;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprenants\App\Exports\GroupeExport;
use Modules\PkgApprenants\App\Imports\GroupeImport;
use Modules\Core\Services\ContextState;

class BaseGroupeController extends AdminController
{
    protected $groupeService;
    protected $apprenantService;
    protected $formateurService;
    protected $anneeFormationService;
    protected $filiereService;

    public function __construct(GroupeService $groupeService, ApprenantService $apprenantService, FormateurService $formateurService, AnneeFormationService $anneeFormationService, FiliereService $filiereService) {
        parent::__construct();
        $this->service  =  $groupeService;
        $this->groupeService = $groupeService;
        $this->apprenantService = $apprenantService;
        $this->formateurService = $formateurService;
        $this->anneeFormationService = $anneeFormationService;
        $this->filiereService = $filiereService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('groupe.index');



        // Extraire les paramètres de recherche, page, et filtres
        $groupes_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('groupes_search', $this->viewState->get("filter.groupe.groupes_search"))],
            $request->except(['groupes_search', 'page', 'sort'])
        );

        // Paginer les groupes
        $groupes_data = $this->groupeService->paginate($groupes_params);

        // Récupérer les statistiques et les champs filtrables
        $groupes_stats = $this->groupeService->getgroupeStats();
        $this->viewState->set('stats.groupe.stats'  , $groupes_stats);
        $groupes_filters = $this->groupeService->getFieldsFilterable();
        $groupe_instance =  $this->groupeService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgApprenants::groupe._table', compact('groupes_data', 'groupes_stats', 'groupes_filters','groupe_instance'))->render();
        }

        return view('PkgApprenants::groupe.index', compact('groupes_data', 'groupes_stats', 'groupes_filters','groupe_instance'));
    }
    public function create() {


        $itemGroupe = $this->groupeService->createInstance();
        

        $filieres = $this->filiereService->all();
        $anneeFormations = $this->anneeFormationService->all();
        $apprenants = $this->apprenantService->all();
        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgApprenants::groupe._fields', compact('itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres'));
        }
        return view('PkgApprenants::groupe.create', compact('itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres'));
    }
    public function store(GroupeRequest $request) {
        $validatedData = $request->validated();
        $groupe = $this->groupeService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $groupe,
                'modelName' => __('PkgApprenants::groupe.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $groupe->id]
            );
        }

        return redirect()->route('groupes.edit',['groupe' => $groupe->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $groupe,
                'modelName' => __('PkgApprenants::groupe.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('groupe.edit_' . $id);


        $itemGroupe = $this->groupeService->find($id);


        $filieres = $this->filiereService->all();
        $anneeFormations = $this->anneeFormationService->all();
        $apprenants = $this->apprenantService->all();
        $formateurs = $this->formateurService->all();


        $this->viewState->set('scope.affectationProjet.groupe_id', $id);


        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_data =  $affectationProjetService->paginate();
        $affectationProjets_stats = $affectationProjetService->getaffectationProjetStats();
        $affectationProjets_filters = $affectationProjetService->getFieldsFilterable();
        $affectationProjet_instance =  $affectationProjetService->createInstance();

        if (request()->ajax()) {
            return view('PkgApprenants::groupe._edit', compact('itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres', 'affectationProjets_data', 'affectationProjets_stats', 'affectationProjets_filters', 'affectationProjet_instance'));
        }

        return view('PkgApprenants::groupe.edit', compact('itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres', 'affectationProjets_data', 'affectationProjets_stats', 'affectationProjets_filters', 'affectationProjet_instance'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('groupe.edit_' . $id);


        $itemGroupe = $this->groupeService->find($id);


        $filieres = $this->filiereService->all();
        $anneeFormations = $this->anneeFormationService->all();
        $apprenants = $this->apprenantService->all();
        $formateurs = $this->formateurService->all();


        $this->viewState->set('scope.affectationProjet.groupe_id', $id);
        

        $affectationProjetService =  new AffectationProjetService();
        $affectationProjets_data =  $affectationProjetService->paginate();
        $affectationProjets_stats = $affectationProjetService->getaffectationProjetStats();
        $this->viewState->set('stats.affectationProjet.stats'  , $affectationProjets_stats);
        $affectationProjets_filters = $affectationProjetService->getFieldsFilterable();
        $affectationProjet_instance =  $affectationProjetService->createInstance();

        if (request()->ajax()) {
            return view('PkgApprenants::groupe._edit', compact('itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres', 'affectationProjets_data', 'affectationProjets_stats', 'affectationProjets_filters', 'affectationProjet_instance'));
        }

        return view('PkgApprenants::groupe.edit', compact('itemGroupe', 'apprenants', 'formateurs', 'anneeFormations', 'filieres', 'affectationProjets_data', 'affectationProjets_stats', 'affectationProjets_filters', 'affectationProjet_instance'));

    }
    public function update(GroupeRequest $request, string $id) {

        $validatedData = $request->validated();
        $groupe = $this->groupeService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgApprenants::groupe.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $groupe->id]
            );
        }

        return redirect()->route('groupes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgApprenants::groupe.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $groupe = $this->groupeService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgApprenants::groupe.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('groupes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $groupe,
                'modelName' =>  __('PkgApprenants::groupe.singular')
                ])
        );

    }

    public function export($format)
    {
        $groupes_data = $this->groupeService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new GroupeExport($groupes_data,'csv'), 'groupe_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new GroupeExport($groupes_data,'xlsx'), 'groupe_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new GroupeImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('groupes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('groupes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprenants::groupe.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getGroupes()
    {
        $groupes = $this->groupeService->all();
        return response()->json($groupes);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $groupe = $this->groupeService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedGroupe = $this->groupeService->dataCalcul($groupe);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedGroupe
        ]);
    }
    

}
