<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;
use Modules\PkgCreationProjet\Services\NatureLivrableService;
use Modules\PkgCreationProjet\Services\LivrableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgCreationProjet\App\Requests\NatureLivrableRequest;
use Modules\PkgCreationProjet\Models\NatureLivrable;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\NatureLivrableExport;
use Modules\PkgCreationProjet\App\Imports\NatureLivrableImport;
use Modules\Core\Services\ContextState;

class BaseNatureLivrableController extends AdminController
{
    protected $natureLivrableService;

    public function __construct(NatureLivrableService $natureLivrableService) {
        parent::__construct();
        $this->service  =  $natureLivrableService;
        $this->natureLivrableService = $natureLivrableService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('natureLivrable.index');
        



         // Extraire les paramètres de recherche, pagination, filtres
        $natureLivrables_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'natureLivrables_search',
                $this->viewState->get("filter.natureLivrable.natureLivrables_search")
            )],
            $request->except(['natureLivrables_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->natureLivrableService->prepareDataForIndexView($natureLivrables_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($natureLivrable_partialViewName, $natureLivrable_compact_value)->render();
        }

        return view('PkgCreationProjet::natureLivrable.index', $natureLivrable_compact_value);
    }
    public function create() {


        $itemNatureLivrable = $this->natureLivrableService->createInstance();
        


        if (request()->ajax()) {
            return view('PkgCreationProjet::natureLivrable._fields', compact('itemNatureLivrable'));
        }
        return view('PkgCreationProjet::natureLivrable.create', compact('itemNatureLivrable'));
    }
    public function store(NatureLivrableRequest $request) {
        $validatedData = $request->validated();
        $natureLivrable = $this->natureLivrableService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' => __('PkgCreationProjet::natureLivrable.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $natureLivrable->id]
            );
        }

        return redirect()->route('natureLivrables.edit',['natureLivrable' => $natureLivrable->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' => __('PkgCreationProjet::natureLivrable.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('natureLivrable.edit_' . $id);


        $itemNatureLivrable = $this->natureLivrableService->edit($id);




        $this->viewState->set('scope.livrable.nature_livrable_id', $id);
        

        $livrableService =  new LivrableService();
        $livrables_view_data = $livrableService->prepareDataForIndexView();
        extract($livrables_view_data);

        if (request()->ajax()) {
            return view('PkgCreationProjet::natureLivrable._edit', array_merge(compact('itemNatureLivrable',),$livrable_compact_value));
        }

        return view('PkgCreationProjet::natureLivrable.edit', array_merge(compact('itemNatureLivrable',),$livrable_compact_value));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('natureLivrable.edit_' . $id);


        $itemNatureLivrable = $this->natureLivrableService->edit($id);




        $this->viewState->set('scope.livrable.nature_livrable_id', $id);
        

        $livrableService =  new LivrableService();
        $livrables_view_data = $livrableService->prepareDataForIndexView();
        extract($livrables_view_data);

        if (request()->ajax()) {
            return view('PkgCreationProjet::natureLivrable._edit', array_merge(compact('itemNatureLivrable',),$livrable_compact_value));
        }

        return view('PkgCreationProjet::natureLivrable.edit', array_merge(compact('itemNatureLivrable',),$livrable_compact_value));


    }
    public function update(NatureLivrableRequest $request, string $id) {

        $validatedData = $request->validated();
        $natureLivrable = $this->natureLivrableService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' =>  __('PkgCreationProjet::natureLivrable.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $natureLivrable->id]
            );
        }

        return redirect()->route('natureLivrables.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' =>  __('PkgCreationProjet::natureLivrable.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $natureLivrable = $this->natureLivrableService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' =>  __('PkgCreationProjet::natureLivrable.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('natureLivrables.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $natureLivrable,
                'modelName' =>  __('PkgCreationProjet::natureLivrable.singular')
                ])
        );

    }

    public function export($format)
    {
        $natureLivrables_data = $this->natureLivrableService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new NatureLivrableExport($natureLivrables_data,'csv'), 'natureLivrable_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new NatureLivrableExport($natureLivrables_data,'xlsx'), 'natureLivrable_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new NatureLivrableImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('natureLivrables.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('natureLivrables.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::natureLivrable.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNatureLivrables()
    {
        $natureLivrables = $this->natureLivrableService->all();
        return response()->json($natureLivrables);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $natureLivrable = $this->natureLivrableService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedNatureLivrable = $this->natureLivrableService->dataCalcul($natureLivrable);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedNatureLivrable
        ]);
    }
    

}