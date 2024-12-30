<?php
// TODO : Add hasmany load eager


namespace Modules\PkgCreationProjet\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCreationProjet\App\Requests\ProjetRequest;
use Modules\PkgCreationProjet\Services\ProjetService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\ProjetExport;
use Modules\PkgCreationProjet\App\Imports\ProjetImport;

class ProjetController extends AdminController
{
    protected $projetService;

    public function __construct(ProjetService $projetService)
    {
        parent::__construct();
        $this->projetService = $projetService;
    }

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $projets_data = $this->projetService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('PkgCreationProjet::projet._table', compact('projets_data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('PkgCreationProjet::projet.index', compact('projets_data'));
    }

    public function create()
    {
        $item = $this->projetService->createInstance();
        return view('PkgCreationProjet::projet.create', compact('item'));
    }

    public function store(ProjetRequest $request)
    {
        $validatedData = $request->validated();
        $projet = $this->projetService->create($validatedData);


        // Après l'insertion de projet : rediretion vers update pour complèter l'insertion 
        // des relation hasMany si 'il existe
        // Redirect to the edit route with the newly created projet ID
        return redirect()->route('projets.edit', ['projet' => $projet->id])
        ->with('info', "Étape 2 : Affectation des compétences");
    }
    public function show(string $id)
    {
        $item = $this->projetService->find($id);
        return view('PkgCreationProjet::projet.show', compact('item'));
    }

    public function edit(string $id)
    {
        // Fetch the project by ID, including related transfertCompetences for optimization
        $item = $this->projetService->find($id)->load('transfertCompetences');
    
        // Check if the project exists
        if (!$item) {
            return redirect()->route('projets.index')->with('error', 'Projet introuvable.');
        }
    
        // Pass the project and its related data to the view
        $transfertCompetences_data =  $item->transfertCompetences()->paginate(10);
        $resources_data = $item->resources()->paginate(10);
        $livrables_data = $item->livrables()->paginate(10);

        return view('PkgCreationProjet::projet.edit', compact('item', 'transfertCompetences_data','resources_data','livrables_data'));
    }
    

    public function update(ProjetRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $projet = $this->projetService->update($id, $validatedData);



        return redirect()->route('projets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $projet = $this->projetService->destroy($id);
        return redirect()->route('projets.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')
                ])
        );
    }

    public function export()
    {
        $projets_data = $this->projetService->all();
        return Excel::download(new ProjetExport($projets_data), 'projet_export.xlsx');
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
}
