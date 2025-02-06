<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Controllers\Base;
use Modules\PkgApprenants\Services\VilleService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgApprenants\App\Requests\VilleRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprenants\App\Exports\VilleExport;
use Modules\PkgApprenants\App\Imports\VilleImport;
use Modules\Core\Services\ContextState;

class BaseVilleController extends AdminController
{
    protected $villeService;

    public function __construct(VilleService $villeService) {
        parent::__construct();
        $this->villeService = $villeService;
    }

    public function index(Request $request) {
        // Extraire les paramètres de recherche, page, et filtres
        $villes_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('villes_search', '')],
            $request->except(['villes_search', 'page', 'sort'])
        );

        // Paginer les villes
        $villes_data = $this->villeService->paginate($villes_params);

        // Récupérer les statistiques et les champs filtrables
        $villes_stats = $this->villeService->getvilleStats();
        $villes_filters = $this->villeService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgApprenants::ville._table', compact('villes_data', 'villes_stats', 'villes_filters'))->render();
        }

        return view('PkgApprenants::ville.index', compact('villes_data', 'villes_stats', 'villes_filters'));
    }
    public function create() {
        $itemVille = $this->villeService->createInstance();


        if (request()->ajax()) {
            return view('PkgApprenants::ville._fields', compact('itemVille'));
        }
        return view('PkgApprenants::ville.create', compact('itemVille'));
    }
    public function store(VilleRequest $request) {
        $validatedData = $request->validated();
        $ville = $this->villeService->create($validatedData);

        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'entity_id' => $ville->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $ville,
                'modelName' => __('PkgApprenants::ville.singular')])
            ]);
        }

        return redirect()->route('villes.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $ville,
                'modelName' => __('PkgApprenants::ville.singular')
            ])
        );
    }
    public function show(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('ville_id', $id);
        
        $itemVille = $this->villeService->find($id);

        if (request()->ajax()) {
            return view('PkgApprenants::ville._fields', compact('itemVille'));
        }

        return view('PkgApprenants::ville.edit', compact('itemVille'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('ville_id', $id);
        
        $itemVille = $this->villeService->find($id);

        if (request()->ajax()) {
            return view('PkgApprenants::ville._fields', compact('itemVille'));
        }

        return view('PkgApprenants::ville.edit', compact('itemVille'));

    }
    public function update(VilleRequest $request, string $id) {

        $validatedData = $request->validated();
        $ville = $this->villeService->update($id, $validatedData);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgApprenants::ville.singular')])
            ]);
        }

        return redirect()->route('villes.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgApprenants::ville.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $ville = $this->villeService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgApprenants::ville.singular')])
            ]);
        }

        return redirect()->route('villes.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $ville,
                'modelName' =>  __('PkgApprenants::ville.singular')
                ])
        );

    }

    public function export($format)
    {
        $villes_data = $this->villeService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new VilleExport($villes_data), 'ville_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new VilleExport($villes_data), 'ville_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new VilleImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('villes.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('villes.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgApprenants::ville.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getVilles()
    {
        $villes = $this->villeService->all();
        return response()->json($villes);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $ville = $this->villeService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedVille = $this->villeService->dataCalcul($ville);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedVille
        ]);
    }
    


}
