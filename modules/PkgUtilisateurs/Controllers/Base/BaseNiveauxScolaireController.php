<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\NiveauxScolaireRequest;
use Modules\PkgUtilisateurs\Services\NiveauxScolaireService;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\NiveauxScolaireExport;
use Modules\PkgUtilisateurs\App\Imports\NiveauxScolaireImport;
use Modules\Core\Services\ContextState;

class BaseNiveauxScolaireController extends AdminController
{
    protected $niveauxScolaireService;

    public function __construct(NiveauxScolaireService $niveauxScolaireService)
    {
        parent::__construct();
        $this->niveauxScolaireService = $niveauxScolaireService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $niveauxScolaire_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $niveauxScolaires_data = $this->niveauxScolaireService->paginate($niveauxScolaire_searchQuery);

        if ($request->ajax()) {
            return view('PkgUtilisateurs::niveauxScolaire._table', compact('niveauxScolaires_data'))->render();
        }

        return view('PkgUtilisateurs::niveauxScolaire.index', compact('niveauxScolaires_data','niveauxScolaire_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemNiveauxScolaire = $this->niveauxScolaireService->createInstance();


        if (request()->ajax()) {
            return view('PkgUtilisateurs::niveauxScolaire._fields', compact('itemNiveauxScolaire'));
        }
        return view('PkgUtilisateurs::niveauxScolaire.create', compact('itemNiveauxScolaire'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(NiveauxScolaireRequest $request)
    {
        $validatedData = $request->validated();
        $niveauxScolaire = $this->niveauxScolaireService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' => __('PkgUtilisateurs::niveauxScolaire.singular')])
            ]);
        }

        return redirect()->route('niveauxScolaires.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' => __('PkgUtilisateurs::niveauxScolaire.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemNiveauxScolaire = $this->niveauxScolaireService->find($id);


        if (request()->ajax()) {
            return view('PkgUtilisateurs::niveauxScolaire._fields', compact('itemNiveauxScolaire'));
        }

        return view('PkgUtilisateurs::niveauxScolaire.show', compact('itemNiveauxScolaire'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemNiveauxScolaire = $this->niveauxScolaireService->find($id);

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('niveauxScolaire_id', $id);


        if (request()->ajax()) {
            return view('PkgUtilisateurs::niveauxScolaire._fields', compact('itemNiveauxScolaire'));
        }

        return view('PkgUtilisateurs::niveauxScolaire.edit', compact('itemNiveauxScolaire'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(NiveauxScolaireRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $niveauxScolaire = $this->niveauxScolaireService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' =>  __('PkgUtilisateurs::niveauxScolaire.singular')])
            ]);
        }

        return redirect()->route('niveauxScolaires.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' =>  __('PkgUtilisateurs::niveauxScolaire.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $niveauxScolaire = $this->niveauxScolaireService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' =>  __('PkgUtilisateurs::niveauxScolaire.singular')])
            ]);
        }

        return redirect()->route('niveauxScolaires.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $niveauxScolaire,
                'modelName' =>  __('PkgUtilisateurs::niveauxScolaire.singular')
                ])
        );
    }

    public function export()
    {
        $niveauxScolaires_data = $this->niveauxScolaireService->all();
        return Excel::download(new NiveauxScolaireExport($niveauxScolaires_data), 'niveauxScolaire_export.xlsx');
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
            'modelNames' =>  __('PkgUtilisateurs::niveauxScolaire.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getNiveauxScolaires()
    {
        $niveauxScolaires = $this->niveauxScolaireService->all();
        return response()->json($niveauxScolaires);
    }
}
