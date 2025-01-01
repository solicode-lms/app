<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgUtilisateurs\App\Requests\ApprenantRequest;
use Modules\PkgUtilisateurs\Services\ApprenantService;
use Modules\PkgUtilisateurs\Services\GroupeService;
use Modules\PkgUtilisateurs\Services\NationaliteService;
use Modules\PkgUtilisateurs\Services\NiveauxScolaireService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgUtilisateurs\App\Exports\ApprenantExport;
use Modules\PkgUtilisateurs\App\Imports\ApprenantImport;
use Modules\Core\Services\ContextState;

class ApprenantController extends AdminController
{
    protected $apprenantService;
    protected $groupeService;
    protected $nationaliteService;
    protected $niveauxScolaireService;

    public function __construct(ApprenantService $apprenantService, GroupeService $groupeService, NationaliteService $nationaliteService, NiveauxScolaireService $niveauxScolaireService)
    {
        parent::__construct();
        $this->apprenantService = $apprenantService;
        $this->groupeService = $groupeService;
        $this->nationaliteService = $nationaliteService;
        $this->niveauxScolaireService = $niveauxScolaireService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $apprenant_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $apprenants_data = $this->apprenantService->paginate($apprenant_searchQuery);

        if ($request->ajax()) {
            return view('PkgUtilisateurs::apprenant._table', compact('apprenants_data'))->render();
        }

        return view('PkgUtilisateurs::apprenant.index', compact('apprenants_data','apprenant_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemApprenant = $this->apprenantService->createInstance();
        $groupes = $this->groupeService->all();
        $nationalites = $this->nationaliteService->all();
        $niveauxScolaires = $this->niveauxScolaireService->all();


        if (request()->ajax()) {
            return view('PkgUtilisateurs::apprenant._fields', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires'));
        }
        return view('PkgUtilisateurs::apprenant.create', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(ApprenantRequest $request)
    {
        $validatedData = $request->validated();
        $apprenant = $this->apprenantService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $apprenant,
                'modelName' => __('PkgUtilisateurs::apprenant.singular')])
            ]);
        }

        return redirect()->route('apprenants.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $apprenant,
                'modelName' => __('PkgUtilisateurs::apprenant.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemApprenant = $this->apprenantService->find($id);
        $groupes = $this->groupeService->all();
        $nationalites = $this->nationaliteService->all();
        $niveauxScolaires = $this->niveauxScolaireService->all();


        if (request()->ajax()) {
            return view('PkgUtilisateurs::apprenant._fields', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires'));
        }

        return view('PkgUtilisateurs::apprenant.show', compact('itemApprenant'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemApprenant = $this->apprenantService->find($id);
        $groupes = $this->groupeService->all();
        $nationalites = $this->nationaliteService->all();
        $niveauxScolaires = $this->niveauxScolaireService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('apprenant_id', $id);


        if (request()->ajax()) {
            return view('PkgUtilisateurs::apprenant._fields', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires'));
        }

        return view('PkgUtilisateurs::apprenant.edit', compact('itemApprenant', 'groupes', 'nationalites', 'niveauxScolaires'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(ApprenantRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $apprenant = $this->apprenantService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $apprenant,
                'modelName' =>  __('PkgUtilisateurs::apprenant.singular')])
            ]);
        }

        return redirect()->route('apprenants.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $apprenant,
                'modelName' =>  __('PkgUtilisateurs::apprenant.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $apprenant = $this->apprenantService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenant,
                'modelName' =>  __('PkgUtilisateurs::apprenant.singular')])
            ]);
        }

        return redirect()->route('apprenants.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $apprenant,
                'modelName' =>  __('PkgUtilisateurs::apprenant.singular')
                ])
        );
    }

    public function export()
    {
        $apprenants_data = $this->apprenantService->all();
        return Excel::download(new ApprenantExport($apprenants_data), 'apprenant_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new ApprenantImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('apprenants.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('apprenants.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('PkgUtilisateurs::apprenant.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getApprenants()
    {
        $apprenants = $this->apprenantService->all();
        return response()->json($apprenants);
    }
}
