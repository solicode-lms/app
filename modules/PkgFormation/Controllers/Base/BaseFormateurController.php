<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgFormation\Services\SpecialiteService;
use Modules\PkgAutorisation\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\PkgFormation\App\Requests\FormateurRequest;
use Modules\PkgFormation\Models\Formateur;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgFormation\App\Exports\FormateurExport;
use Modules\PkgFormation\App\Imports\FormateurImport;
use Modules\Core\Services\ContextState;

class BaseFormateurController extends AdminController
{
    protected $formateurService;
    protected $groupeService;
    protected $specialiteService;
    protected $userService;

    public function __construct(FormateurService $formateurService, GroupeService $groupeService, SpecialiteService $specialiteService, UserService $userService) {
        parent::__construct();
        $this->formateurService = $formateurService;
        $this->groupeService = $groupeService;
        $this->specialiteService = $specialiteService;
        $this->userService = $userService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('formateur.index');


        // Extraire les paramètres de recherche, page, et filtres
        $formateurs_params = array_merge(
            $request->only(['page','sort']),
            ['search' => $request->get('formateurs_search', $this->viewState->get("filter.formateur.formateurs_search"))],
            $request->except(['formateurs_search', 'page', 'sort'])
        );

        // Paginer les formateurs
        $formateurs_data = $this->formateurService->paginate($formateurs_params);

        // Récupérer les statistiques et les champs filtrables
        $formateurs_stats = $this->formateurService->getformateurStats();
        $formateurs_filters = $this->formateurService->getFieldsFilterable();
        $formateur_instance =  $this->formateurService->createInstance();
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view('PkgFormation::formateur._table', compact('formateurs_data', 'formateurs_stats', 'formateurs_filters','formateur_instance'))->render();
        }

        return view('PkgFormation::formateur.index', compact('formateurs_data', 'formateurs_stats', 'formateurs_filters','formateur_instance'));
    }
    public function create() {
        $itemFormateur = $this->formateurService->createInstance();
        
        $specialites = $this->specialiteService->all();
        $groupes = $this->groupeService->all();
        $users = $this->userService->all();

        if (request()->ajax()) {
            return view('PkgFormation::formateur._fields', compact('itemFormateur', 'groupes', 'specialites', 'users'));
        }
        return view('PkgFormation::formateur.create', compact('itemFormateur', 'groupes', 'specialites', 'users'));
    }
    public function store(FormateurRequest $request) {
        $validatedData = $request->validated();
        $formateur = $this->formateurService->create($validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.addSuccess', [
                'entityToString' => $formateur,
                'modelName' => __('PkgFormation::formateur.singular')]);
        
            return JsonResponseHelper::success(
             $message,
             ['entity_id' => $formateur->id]
            );
        }

        return redirect()->route('formateurs.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $formateur,
                'modelName' => __('PkgFormation::formateur.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('formateur.edit_' . $id);
     
        $itemFormateur = $this->formateurService->find($id);
  
        $groupes = $this->groupeService->all();
        $specialites = $this->specialiteService->all();
        $users = $this->userService->all();


        if (request()->ajax()) {
            return view('PkgFormation::formateur._fields', compact('itemFormateur', 'groupes', 'specialites', 'users'));
        }

        return view('PkgFormation::formateur.edit', compact('itemFormateur', 'groupes', 'specialites', 'users'));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('formateur.edit_' . $id);

        $itemFormateur = $this->formateurService->find($id);

        $specialites = $this->specialiteService->all();
        $groupes = $this->groupeService->all();
        $users = $this->userService->all();


        if (request()->ajax()) {
            return view('PkgFormation::formateur._fields', compact('itemFormateur', 'groupes', 'specialites', 'users'));
        }

        return view('PkgFormation::formateur.edit', compact('itemFormateur', 'groupes', 'specialites', 'users'));

    }
    public function update(FormateurRequest $request, string $id) {

        $validatedData = $request->validated();
        $formateur = $this->formateurService->update($id, $validatedData);

        if ($request->ajax()) {
             $message = __('Core::msg.updateSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgFormation::formateur.singular')]);
            
            return JsonResponseHelper::success(
                $message,
                ['entity_id' => $formateur->id]
            );
        }

        return redirect()->route('formateurs.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgFormation::formateur.singular')
                ])
        );

    }
    public function destroy(Request $request, string $id) {

        $formateur = $this->formateurService->destroy($id);

        if ($request->ajax()) {
            $message = __('Core::msg.deleteSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgFormation::formateur.singular')]);
            

            return JsonResponseHelper::success(
                $message
            );
        }

        return redirect()->route('formateurs.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgFormation::formateur.singular')
                ])
        );

    }

    public function export($format)
    {
        $formateurs_data = $this->formateurService->all();
        
        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new FormateurExport($formateurs_data,'csv'), 'formateur_export.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            return Excel::download(new FormateurExport($formateurs_data,'xlsx'), 'formateur_export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
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
            Excel::import(new FormateurImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('formateurs.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('formateurs.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgFormation::formateur.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFormateurs()
    {
        $formateurs = $this->formateurService->all();
        return response()->json($formateurs);
    }


    public function dataCalcul(Request $request)
    {

        // Extraire les données de la requête
        $data = $request->all();

        $formateur = $this->formateurService->createInstance($data);
    
        // Mise à jour des attributs via le service
        $updatedFormateur = $this->formateurService->dataCalcul($formateur);
    
        return response()->json([
            'success' => true,
            'entity' => $updatedFormateur
        ]);
    }
    
    public function initPassword(Request $request, string $id) {
        $formateur = $this->formateurService->initPassword($id);
        if ($request->ajax()) {
            $message = "Le mot de passe a été modifier avec succès";
            return JsonResponseHelper::success(
                $message
            );
        }
        return redirect()->route('Formateur.index')->with(
            'success',
            "Le mot de passe a été modifier avec succès"
        );
    }
    
}
