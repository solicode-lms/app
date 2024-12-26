<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCreationProjet\App\Requests\TransfertCompetenceRequest;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Modules\PkgCompetences\Services\TechnologyService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\TransfertCompetenceExport;
use Modules\PkgCreationProjet\App\Imports\TransfertCompetenceImport;

class TransfertCompetenceController extends AdminController
{
    protected $transfertCompetenceService;
    protected $technologyService;

    public function __construct(TransfertCompetenceService $transfertCompetenceService, TechnologyService $technologyService)
    {
        parent::__construct();
        $this->transfertCompetenceService = $transfertCompetenceService;
        $this->technologyService = $technologyService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->transfertCompetenceService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgCreationProjet::transfertCompetence._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgCreationProjet::transfertCompetence.index', compact('data'));
    }

    public function create()
    {
        $item = $this->transfertCompetenceService->createInstance();
        $technologies = $this->technologyService->all();
        return view('PkgCreationProjet::transfertCompetence.create', compact('item', 'technologies'));
    }

    public function store(TransfertCompetenceRequest $request)
    {
        $validatedData = $request->validated();
        $transfertCompetence = $this->transfertCompetenceService->create($validatedData);

        if ($request->has('technologies')) {
            $transfertCompetence->technologies()->sync($request->input('technologies'));
        }

        return redirect()->route('transfertCompetences.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $transfertCompetence,
            'modelName' => __('PkgCreationProjet::transfertCompetence.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->transfertCompetenceService->find($id);
        return view('PkgCreationProjet::transfertcompetence.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->transfertCompetenceService->find($id);
        $technologies = $this->technologyService->all();
        return view('PkgCreationProjet::transfertCompetence.edit', compact('item', 'technologies'));
    }

    public function update(TransfertCompetenceRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $transfertcompetence = $this->transfertCompetenceService->update($id, $validatedData);


        if ($request->has('technologies')) {
            $transfertCompetence->technologies()->sync($request->input('technologies'));
        }

        return redirect()->route('transfertCompetences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $transfertcompetence,
                'modelName' =>  __('PkgCreationProjet::transfertcompetence.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $transfertcompetence = $this->transfertCompetenceService->destroy($id);
        return redirect()->route('transfertCompetences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $transfertcompetence,
                'modelName' =>  __('PkgCreationProjet::transfertcompetence.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->transfertCompetenceService->all();
        return Excel::download(new TransfertCompetenceExport($data), 'transfertCompetence_export.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new TransfertCompetenceImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('transfertCompetences.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('transfertCompetences.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgCreationProjet::transfertcompetence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getTransfertCompetences()
    {
        $transfertCompetences = $this->transfertCompetenceService->all();
        return response()->json($transfertCompetences);
    }
}
