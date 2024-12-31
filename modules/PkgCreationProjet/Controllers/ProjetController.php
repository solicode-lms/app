<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCreationProjet\App\Requests\ProjetRequest;
use Modules\PkgCreationProjet\Services\ProjetService;
use Modules\PkgUtilisateurs\Services\FormateurService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\ProjetExport;
use Modules\PkgCreationProjet\App\Imports\ProjetImport;

class ProjetController extends AdminController
{
    protected $projetService;
    protected $formateurService;

    public function __construct(ProjetService $projetService, FormateurService $formateurService)
    {
        parent::__construct();
        $this->projetService = $projetService;
        $this->formateurService = $formateurService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $projet_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $projets_data = $this->projetService->paginate($projet_searchQuery);

        if ($request->ajax()) {
            return view('PkgCreationProjet::projet._table', compact('projets_data'))->render();
        }

        return view('PkgCreationProjet::projet.index', compact('projets_data','projet_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemProjet = $this->projetService->createInstance();
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._fields', compact('itemProjet', 'formateurs'));
        }
        return view('PkgCreationProjet::projet.create', compact('itemProjet', 'formateurs'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(ProjetRequest $request)
    {
        $validatedData = $request->validated();
        $projet = $this->projetService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $projet,
                'modelName' => __('PkgCreationProjet::projet.singular')])
            ]);
        }

        return redirect()->route('projets.edit',['projet' => $projet->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $projet,
                'modelName' => __('PkgCreationProjet::projet.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemProjet = $this->projetService->find($id);
        $formateurs = $this->formateurService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._fields', compact('itemProjet', 'formateurs'));
        }

        return view('PkgCreationProjet::projet.show', compact('itemProjet'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemProjet = $this->projetService->find($id);
        $formateurs = $this->formateurService->all();
         $livrables_data =  $itemProjet->livrables()->paginate(10);
         $resources_data =  $itemProjet->resources()->paginate(10);
         $transfertCompetences_data =  $itemProjet->transfertCompetences()->paginate(10);

        if (request()->ajax()) {
            return view('PkgCreationProjet::projet._fields', compact('itemProjet', 'formateurs', 'livrables_data', 'resources_data', 'transfertCompetences_data'));
        }

        return view('PkgCreationProjet::projet.edit', compact('itemProjet', 'formateurs', 'livrables_data', 'resources_data', 'transfertCompetences_data'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(ProjetRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $projet = $this->projetService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')])
            ]);
        }

        return redirect()->route('projets.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $projet = $this->projetService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $projet,
                'modelName' =>  __('PkgCreationProjet::projet.singular')])
            ]);
        }

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
