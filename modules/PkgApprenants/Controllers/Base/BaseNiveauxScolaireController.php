<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\NiveauxScolaireService;
use Modules\PkgApprenants\Services\ApprenantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgApprenants\App\Requests\NiveauxScolaireRequest;
use Modules\PkgApprenants\Models\NiveauxScolaire;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprenants\App\Exports\NiveauxScolaireExport;
use Modules\PkgApprenants\App\Imports\NiveauxScolaireImport;
use Modules\Core\Services\ContextState;

class BaseNiveauxScolaireController extends AdminController
{
    protected $niveauxScolaireService;

    public function __construct(NiveauxScolaireService $niveauxScolaireService) {
        parent::__construct();
        $this->service  =  $niveauxScolaireService;
        $this->niveauxScolaireService = $niveauxScolaireService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('niveauxScolaire.index');
        



         // Extraire les paramètres de recherche, pagination, filtres
        $niveauxScolaires_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'niveauxScolaires_search',
                $this->viewState->get("filter.niveauxScolaire.niveauxScolaires_search")
            )],
            $request->except(['niveauxScolaires_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->niveauxScolaireService->prepareDataForIndexView($niveauxScolaires_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($niveauxScolaire_partialViewName, $niveauxScolaire_compact_value)->render();
        }

        return view('PkgApprenants::niveauxScolaire.index', $niveauxScolaire_compact_value);
    }
    public function create() {


        $itemNiveauxScolaire = $this->niveauxScolaireService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgApprenants::niveauxScolaire._fields', compact('itemNiveauxScolaire'));
        }
        return view('PkgApprenants::niveauxScolaire.create', compact('itemNiveauxScolaire'));
    }
    public function store(NiveauxScolaireRequest $request) {
        $validatedData = $request->validated();
        $niveauxScolaire = $this->niveauxScolaireService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' => __('PkgApprenants::niveauxScolaire.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $niveauxScolaire->id]
            );
        }

        return redirect()->route('niveauxScolaires.edit',['niveauxScolaire' => $niveauxScolaire->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' => __('PkgApprenants::niveauxScolaire.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('niveauxScolaire.edit_' . $id);


        $itemNiveauxScolaire = $this->niveauxScolaireService->edit($id);




        $this->viewState->set('scope.apprenant.niveaux_scolaire_id', $id);
        

        $apprenantService =  new ApprenantService();
        $apprenants_view_data = $apprenantService->prepareDataForIndexView();
        extract($apprenants_view_data);

        if (request()->ajax()) {
            return view('PkgApprenants::niveauxScolaire._edit', array_merge(compact('itemNiveauxScolaire',),$apprenant_compact_value));
        }

        return view('PkgApprenants::niveauxScolaire.edit', array_merge(compact('itemNiveauxScolaire',),$apprenant_compact_value));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('niveauxScolaire.edit_' . $id);


        $itemNiveauxScolaire = $this->niveauxScolaireService->edit($id);




        $this->viewState->set('scope.apprenant.niveaux_scolaire_id', $id);
        

        $apprenantService =  new ApprenantService();
        $apprenants_view_data = $apprenantService->prepareDataForIndexView();
        extract($apprenants_view_data);

        if (request()->ajax()) {
            return view('PkgApprenants::niveauxScolaire._edit', array_merge(compact('itemNiveauxScolaire',),$apprenant_compact_value));
        }

        return view('PkgApprenants::niveauxScolaire.edit', array_merge(compact('itemNiveauxScolaire',),$apprenant_compact_value));


    }
    public function update(NiveauxScolaireRequest $request, string $id) {

        $validatedData = $request->validated();
        $niveauxScolaire = $this->niveauxScolaireService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' =>  __('PkgApprenants::niveauxScolaire.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $niveauxScolaire->id]
            );
        }

        return redirect()->route('niveauxScolaires.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' =>  __('PkgApprenants::niveauxScolaire.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $niveauxScolaire = $this->niveauxScolaireService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' =>  __('PkgApprenants::niveauxScolaire.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('niveauxScolaires.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' =>  __('PkgApprenants::niveauxScolaire.singular')
                ])
        );

    }

    public function export($format)
    {
        $niveauxScolaires_data = $this->niveauxScolaireService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new NiveauxScolaireExport($niveauxScolaires_data,'csv'), 'niveauxScolaire_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new NiveauxScolaireExport($niveauxScolaires_data,'xlsx'), 'niveauxScolaire_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new NiveauxScolaireImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('niveauxScolaires.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('niveauxScolaires.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprenants::niveauxScolaire.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNiveauxScolaires()
    {
        $niveauxScolaires = $this->niveauxScolaireService->all();
        return response()->json($niveauxScolaires);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $niveauxScolaire = $this->niveauxScolaireService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedNiveauxScolaire = $this->niveauxScolaireService->dataCalcul($niveauxScolaire);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedNiveauxScolaire
        ]);
    }
    

}