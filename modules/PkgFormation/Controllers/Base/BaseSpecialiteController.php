<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\SpecialiteService;
use Modules\PkgFormation\Services\FormateurService;
use Illuminate\Http\Request;
use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgFormation\App\Requests\SpecialiteRequest;
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
        // Extraire les paramètres de recherche, page, et filtres
        $specialites_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('specialites_search', '')],
            $request->except(['specialites_search', 'page', 'sort'])
        );

        // Paginer les specialites
        $specialites_data = $this->specialiteService->paginate($specialites_params);

        // Récupérer les statistiques et les champs filtrables
        $specialites_stats = $this->specialiteService->getspecialiteStats();
        $specialites_filters = $this->specialiteService->getFieldsFilterable();

        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgFormation::specialite._table', compact('specialites_data', 'specialites_stats', 'specialites_filters'))->render();
        }

        return view('PkgFormation::specialite.index', compact('specialites_data', 'specialites_stats', 'specialites_filters'));
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


        if ($request->has('formateurs')) {
            $specialite->formateurs()->sync($request->input('formateurs'));
        }


        if ($request->ajax()) {
            return response()->json(['success' => true, 
            'specialite_id' => $specialite->id,
            'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $specialite,
                'modelName' => __('PkgFormation::specialite.singular')])
            ]);
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

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('specialite_id', $id);
        
        $itemSpecialite = $this->specialiteService->find($id);
        $formateurs = $this->formateurService->all();

        if (request()->ajax()) {
            return view('PkgFormation::specialite._fields', compact('itemSpecialite', 'formateurs'));
        }

        return view('PkgFormation::specialite.edit', compact('itemSpecialite', 'formateurs'));

    }
    public function edit(string $id) {

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('specialite_id', $id);
        
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

        $specialite->formateurs()->sync($request->input('formateurs'));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgFormation::specialite.singular')])
            ]);
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
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgFormation::specialite.singular')])
            ]);
        }

        return redirect()->route('specialites.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $specialite,
                'modelName' =>  __('PkgFormation::specialite.singular')
                ])
        );

    }

    public function export()
    {
        $specialites_data = $this->specialiteService->all();
        return Excel::download(new SpecialiteExport($specialites_data), 'specialite_export.xlsx');
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

}
