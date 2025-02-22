<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\SpecialiteService;
use Modules\PkgFormation\Services\FormateurService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgFormation\App\Requests\SpecialiteRequest;
use Modules\PkgFormation\Models\Specialite;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgFormation\App\Exports\SpecialiteExport;
use Modules\PkgFormation\App\Imports\SpecialiteImport;
use Modules\Core\Services\ContextState;

class BaseSpecialiteController extends AdminController
{
    protected $specialiteService;
    protected $formateurService;

    public function __construct(SpecialiteService $specialiteService, FormateurService $formateurService) {
        parent::__construct();
        $this->specialiteService = $specialiteService;
        $this->formateurService = $formateurService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('specialite.index');


        // Extraire les paramètres de recherche, page, et filtres
        $specialites_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('specialites_search', $this->viewState->get("filter.specialite.specialites_search"))],
            $request->except(['specialites_search', 'page', 'sort'])
        );

        // Paginer les specialites
        $specialites_data = $this->specialiteService->paginate($specialites_params);

        // Récupérer les statistiques et les champs filtrables
        $specialites_stats = $this->specialiteService->getspecialiteStats();
        $specialites_filters = $this->specialiteService->getFieldsFilterable();
        $specialite_instance =  $this->specialiteService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgFormation::specialite._table', compact('specialites_data', 'specialites_stats', 'specialites_filters','specialite_instance'))->render();
        }

        return view('PkgFormation::specialite.index', compact('specialites_data', 'specialites_stats', 'specialites_filters','specialite_instance'));
    }
    public function create() {


        $itemSpecialite = $this->specialiteService->createInstance();
        
        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgFormation::specialite._fields', compact('itemSpecialite', 'formateurs'));
        }
        return view('PkgFormation::specialite.create', compact('itemSpecialite', 'formateurs'));
    }
    public function store(SpecialiteRequest $request) {
        $validatedData = $request->validated();
        $specialite = $this->specialiteService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $specialite,
                'modelName' => __('PkgFormation::specialite.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $specialite->id]
            );
        }

        return redirect()->route('specialites.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $specialite,
                'modelName' => __('PkgFormation::specialite.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('specialite.edit_' . $id);

        $itemSpecialite = $this->specialiteService->find($id);

        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgFormation::specialite._fields', compact('itemSpecialite', 'formateurs'));
        }

        return view('PkgFormation::specialite.edit', compact('itemSpecialite', 'formateurs'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('specialite.edit_' . $id);

        $itemSpecialite = $this->specialiteService->find($id);

        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgFormation::specialite._fields', compact('itemSpecialite', 'formateurs'));
        }

        return view('PkgFormation::specialite.edit', compact('itemSpecialite', 'formateurs'));

    }
    public function update(SpecialiteRequest $request, string $id) {

        $validatedData = $request->validated();
        $specialite = $this->specialiteService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgFormation::specialite.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $specialite->id]
            );
        }

        return redirect()->route('specialites.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgFormation::specialite.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $specialite = $this->specialiteService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgFormation::specialite.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('specialites.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgFormation::specialite.singular')
                ])
        );

    }

    public function export($format)
    {
        $specialites_data = $this->specialiteService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new SpecialiteExport($specialites_data,'csv'), 'specialite_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new SpecialiteExport($specialites_data,'xlsx'), 'specialite_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new SpecialiteImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('specialites.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('specialites.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgFormation::specialite.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSpecialites()
    {
        $specialites = $this->specialiteService->all();
        return response()->json($specialites);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $specialite = $this->specialiteService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedSpecialite = $this->specialiteService->dataCalcul($specialite);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedSpecialite
        ]);
    }
    

}
