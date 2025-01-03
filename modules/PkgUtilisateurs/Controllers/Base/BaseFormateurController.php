<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\FormateurRequest;
use Modules\PkgUtilisateurs\Services\FormateurService;
use Modules\PkgUtilisateurs\Services\GroupeService;
use Modules\PkgUtilisateurs\Services\SpecialiteService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\FormateurExport;
use Modules\PkgUtilisateurs\App\Imports\FormateurImport;
use Modules\Core\Services\ContextState;

class BaseFormateurController extends AdminController
{
    protected $formateurService;
    protected $groupeService;
    protected $specialiteService;

    public function __construct(FormateurService $formateurService, GroupeService $groupeService, SpecialiteService $specialiteService)
    {
        parent::__construct();
        $this->formateurService = $formateurService;
        $this->groupeService = $groupeService;
        $this->specialiteService = $specialiteService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $formateur_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $formateurs_data = $this->formateurService->paginate($formateur_searchQuery);

        if ($request->ajax()) {
            return view('PkgUtilisateurs::formateur._table', compact('formateurs_data'))->render();
        }

        return view('PkgUtilisateurs::formateur.index', compact('formateurs_data','formateur_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemFormateur = $this->formateurService->createInstance();
        $groupes = $this->groupeService->all();
        $specialites = $this->specialiteService->all();


        if (request()->ajax()) {
            return view('PkgUtilisateurs::formateur._fields', compact('itemFormateur', 'groupes', 'specialites'));
        }
        return view('PkgUtilisateurs::formateur.create', compact('itemFormateur', 'groupes', 'specialites'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(FormateurRequest $request)
    {
        $validatedData = $request->validated();
        $formateur = $this->formateurService->create($validatedData);


        if ($request->has('groupes')) {
            $formateur->groupes()->sync($request->input('groupes'));
        }
        if ($request->has('specialites')) {
            $formateur->specialites()->sync($request->input('specialites'));
        }


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $formateur,
                'modelName' => __('PkgUtilisateurs::formateur.singular')])
            ]);
        }

        return redirect()->route('formateurs.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $formateur,
                'modelName' => __('PkgUtilisateurs::formateur.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemFormateur = $this->formateurService->find($id);
        $groupes = $this->groupeService->all();
        $specialites = $this->specialiteService->all();


        if (request()->ajax()) {
            return view('PkgUtilisateurs::formateur._fields', compact('itemFormateur', 'groupes', 'specialites'));
        }

        return view('PkgUtilisateurs::formateur.show', compact('itemFormateur'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemFormateur = $this->formateurService->find($id);
        $groupes = $this->groupeService->all();
        $specialites = $this->specialiteService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('formateur_id', $id);


        if (request()->ajax()) {
            return view('PkgUtilisateurs::formateur._fields', compact('itemFormateur', 'groupes', 'specialites'));
        }

        return view('PkgUtilisateurs::formateur.edit', compact('itemFormateur', 'groupes', 'specialites'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(FormateurRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $formateur = $this->formateurService->update($id, $validatedData);


        $formateur->groupes()->sync($request->input('groupes'));
        $formateur->specialites()->sync($request->input('specialites'));


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgUtilisateurs::formateur.singular')])
            ]);
        }

        return redirect()->route('formateurs.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgUtilisateurs::formateur.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $formateur = $this->formateurService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgUtilisateurs::formateur.singular')])
            ]);
        }

        return redirect()->route('formateurs.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $formateur,
                'modelName' =>  __('PkgUtilisateurs::formateur.singular')
                ])
        );
    }

    public function export()
    {
        $formateurs_data = $this->formateurService->all();
        return Excel::download(new FormateurExport($formateurs_data), 'formateur_export.xlsx');
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
            'modelNames' =>  __('PkgUtilisateurs::formateur.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFormateurs()
    {
        $formateurs = $this->formateurService->all();
        return response()->json($formateurs);
    }
}
