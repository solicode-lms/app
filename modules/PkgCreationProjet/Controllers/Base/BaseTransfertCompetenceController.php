<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Controllers\Base;

use Modules\Core\Controllers\Base\AdminController;
use Modules\PkgCreationProjet\App\Requests\TransfertCompetenceRequest;
use Modules\PkgCreationProjet\Services\TransfertCompetenceService;
use Modules\PkgCompetences\Services\TechnologyService;
use Modules\PkgCompetences\Services\AppreciationService;
use Modules\PkgCompetences\Services\CompetenceService;
use Modules\PkgCreationProjet\Services\ProjetService;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgCreationProjet\App\Exports\TransfertCompetenceExport;
use Modules\PkgCreationProjet\App\Imports\TransfertCompetenceImport;
use Modules\Core\Services\ContextState;

class BaseTransfertCompetenceController extends AdminController
{
    protected $transfertCompetenceService;
    protected $technologyService;
    protected $appreciationService;
    protected $competenceService;
    protected $projetService;

    public function __construct(TransfertCompetenceService $transfertCompetenceService, TechnologyService $technologyService, AppreciationService $appreciationService, CompetenceService $competenceService, ProjetService $projetService)
    {
        parent::__construct();
        $this->transfertCompetenceService = $transfertCompetenceService;
        $this->technologyService = $technologyService;
        $this->appreciationService = $appreciationService;
        $this->competenceService = $competenceService;
        $this->projetService = $projetService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $transfertCompetence_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $transfertCompetences_data = $this->transfertCompetenceService->paginate($transfertCompetence_searchQuery);

        if ($request->ajax()) {
            return view('PkgCreationProjet::transfertCompetence._table', compact('transfertCompetences_data'))->render();
        }

        return view('PkgCreationProjet::transfertCompetence.index', compact('transfertCompetences_data','transfertCompetence_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemTransfertCompetence = $this->transfertCompetenceService->createInstance();
        $technologies = $this->technologyService->all();
        $appreciations = $this->appreciationService->all();
        $competences = $this->competenceService->all();
        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::transfertCompetence._fields', compact('itemTransfertCompetence', 'technologies', 'appreciations', 'competences', 'projets'));
        }
        return view('PkgCreationProjet::transfertCompetence.create', compact('itemTransfertCompetence', 'technologies', 'appreciations', 'competences', 'projets'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(TransfertCompetenceRequest $request)
    {
        $validatedData = $request->validated();
        $transfertCompetence = $this->transfertCompetenceService->create($validatedData);


        if ($request->has('technologies')) {
            $transfertCompetence->technologies()->sync($request->input('technologies'));
        }


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $transfertCompetence,
                'modelName' => __('PkgCreationProjet::transfertCompetence.singular')])
            ]);
        }

        return redirect()->route('transfertCompetences.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $transfertCompetence,
                'modelName' => __('PkgCreationProjet::transfertCompetence.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemTransfertCompetence = $this->transfertCompetenceService->find($id);
        $technologies = $this->technologyService->all();
        $appreciations = $this->appreciationService->all();
        $competences = $this->competenceService->all();
        $projets = $this->projetService->all();


        if (request()->ajax()) {
            return view('PkgCreationProjet::transfertCompetence._fields', compact('itemTransfertCompetence', 'technologies', 'appreciations', 'competences', 'projets'));
        }

        return view('PkgCreationProjet::transfertCompetence.show', compact('itemTransfertCompetence'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemTransfertCompetence = $this->transfertCompetenceService->find($id);
        $technologies = $this->technologyService->all();
        $appreciations = $this->appreciationService->all();
        $competences = $this->competenceService->all();
        $projets = $this->projetService->all();

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('transfertCompetence_id', $id);


        if (request()->ajax()) {
            return view('PkgCreationProjet::transfertCompetence._fields', compact('itemTransfertCompetence', 'technologies', 'appreciations', 'competences', 'projets'));
        }

        return view('PkgCreationProjet::transfertCompetence.edit', compact('itemTransfertCompetence', 'technologies', 'appreciations', 'competences', 'projets'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(TransfertCompetenceRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $transfertCompetence = $this->transfertCompetenceService->update($id, $validatedData);


        $transfertCompetence->technologies()->sync($request->input('technologies'));


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $transfertCompetence,
                'modelName' =>  __('PkgCreationProjet::transfertCompetence.singular')])
            ]);
        }

        return redirect()->route('transfertCompetences.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $transfertCompetence,
                'modelName' =>  __('PkgCreationProjet::transfertCompetence.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $transfertCompetence = $this->transfertCompetenceService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $transfertCompetence,
                'modelName' =>  __('PkgCreationProjet::transfertCompetence.singular')])
            ]);
        }

        return redirect()->route('transfertCompetences.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $transfertCompetence,
                'modelName' =>  __('PkgCreationProjet::transfertCompetence.singular')
                ])
        );
    }

    public function export()
    {
        $transfertCompetences_data = $this->transfertCompetenceService->all();
        return Excel::download(new TransfertCompetenceExport($transfertCompetences_data), 'transfertCompetence_export.xlsx');
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
            'modelNames' =>  __('PkgCreationProjet::transfertCompetence.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getTransfertCompetences()
    {
        $transfertCompetences = $this->transfertCompetenceService->all();
        return response()->json($transfertCompetences);
    }
}
