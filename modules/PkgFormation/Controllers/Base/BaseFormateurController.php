<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgFormation\Controllers\Base;
use Modules\PkgFormation\Services\FormateurService;
use Modules\PkgApprenants\Services\GroupeService;
use Modules\PkgFormation\Services\SpecialiteService;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgAutoformation\Services\ChapitreService;
use Modules\PkgGestionTaches\Services\CommentaireRealisationTacheService;
use Modules\PkgGestionTaches\Services\EtatRealisationTacheService;
use Modules\PkgAutoformation\Services\EtatChapitreService;
use Modules\PkgAutoformation\Services\EtatFormationService;
use Modules\PkgGestionTaches\Services\LabelRealisationTacheService;
use Modules\PkgAutoformation\Services\FormationService;
use Modules\PkgGestionTaches\Services\PrioriteTacheService;
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
        $this->service  =  $formateurService;
        $this->formateurService = $formateurService;
        $this->groupeService = $groupeService;
        $this->specialiteService = $specialiteService;
        $this->userService = $userService;
    }

    public function index(Request $request) {
        
        $this->viewState->setContextKeyIfEmpty('formateur.index');
        



         // Extraire les paramètres de recherche, pagination, filtres
        $formateurs_params = array_merge(
            $request->only(['page', 'sort']),
            ['search' => $request->get(
                'formateurs_search',
                $this->viewState->get("filter.formateur.formateurs_search")
            )],
            $request->except(['formateurs_search', 'page', 'sort'])
        );

        // prepareDataForIndexView
        $tcView = $this->formateurService->prepareDataForIndexView($formateurs_params);
        extract($tcView); // Toutes les variables sont injectées automatiquement
        
        // Retourner la vue ou les données pour une requête AJAX
        if ($request->ajax()) {
            return view($formateur_partialViewName, $formateur_compact_value)->render();
        }

        return view('PkgFormation::formateur.index', $formateur_compact_value);
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

        return redirect()->route('formateurs.edit',['formateur' => $formateur->id])->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $formateur,
                'modelName' => __('PkgFormation::formateur.singular')
            ])
        );
    }
    public function show(string $id) {

        $this->viewState->setContextKey('formateur.edit_' . $id);


        $itemFormateur = $this->formateurService->edit($id);


        $specialites = $this->specialiteService->all();
        $groupes = $this->groupeService->all();
        $users = $this->userService->all();


        $this->viewState->set('scope.chapitre.formateur_id', $id);
        

        $chapitreService =  new ChapitreService();
        $chapitres_view_data = $chapitreService->prepareDataForIndexView();
        extract($chapitres_view_data);

        $this->viewState->set('scope.commentaireRealisationTache.formateur_id', $id);
        

        $commentaireRealisationTacheService =  new CommentaireRealisationTacheService();
        $commentaireRealisationTaches_view_data = $commentaireRealisationTacheService->prepareDataForIndexView();
        extract($commentaireRealisationTaches_view_data);

        $this->viewState->set('scope.etatRealisationTache.formateur_id', $id);
        

        $etatRealisationTacheService =  new EtatRealisationTacheService();
        $etatRealisationTaches_view_data = $etatRealisationTacheService->prepareDataForIndexView();
        extract($etatRealisationTaches_view_data);

        $this->viewState->set('scope.etatChapitre.formateur_id', $id);
        

        $etatChapitreService =  new EtatChapitreService();
        $etatChapitres_view_data = $etatChapitreService->prepareDataForIndexView();
        extract($etatChapitres_view_data);

        $this->viewState->set('scope.etatFormation.formateur_id', $id);
        

        $etatFormationService =  new EtatFormationService();
        $etatFormations_view_data = $etatFormationService->prepareDataForIndexView();
        extract($etatFormations_view_data);

        $this->viewState->set('scope.labelRealisationTache.formateur_id', $id);
        

        $labelRealisationTacheService =  new LabelRealisationTacheService();
        $labelRealisationTaches_view_data = $labelRealisationTacheService->prepareDataForIndexView();
        extract($labelRealisationTaches_view_data);

        $this->viewState->set('scope.formation.formateur_id', $id);
        

        $formationService =  new FormationService();
        $formations_view_data = $formationService->prepareDataForIndexView();
        extract($formations_view_data);

        $this->viewState->set('scope.prioriteTache.formateur_id', $id);
        

        $prioriteTacheService =  new PrioriteTacheService();
        $prioriteTaches_view_data = $prioriteTacheService->prepareDataForIndexView();
        extract($prioriteTaches_view_data);

        if (request()->ajax()) {
            return view('PkgFormation::formateur._edit', array_merge(compact('itemFormateur','groupes', 'specialites', 'users'),$chapitre_compact_value, $commentaireRealisationTache_compact_value, $etatRealisationTache_compact_value, $etatChapitre_compact_value, $etatFormation_compact_value, $labelRealisationTache_compact_value, $formation_compact_value, $prioriteTache_compact_value));
        }

        return view('PkgFormation::formateur.edit', array_merge(compact('itemFormateur','groupes', 'specialites', 'users'),$chapitre_compact_value, $commentaireRealisationTache_compact_value, $etatRealisationTache_compact_value, $etatChapitre_compact_value, $etatFormation_compact_value, $labelRealisationTache_compact_value, $formation_compact_value, $prioriteTache_compact_value));

    }
    public function edit(string $id) {

        $this->viewState->setContextKey('formateur.edit_' . $id);


        $itemFormateur = $this->formateurService->edit($id);


        $specialites = $this->specialiteService->all();
        $groupes = $this->groupeService->all();
        $users = $this->userService->all();


        $this->viewState->set('scope.chapitre.formateur_id', $id);
        

        $chapitreService =  new ChapitreService();
        $chapitres_view_data = $chapitreService->prepareDataForIndexView();
        extract($chapitres_view_data);

        $this->viewState->set('scope.commentaireRealisationTache.formateur_id', $id);
        

        $commentaireRealisationTacheService =  new CommentaireRealisationTacheService();
        $commentaireRealisationTaches_view_data = $commentaireRealisationTacheService->prepareDataForIndexView();
        extract($commentaireRealisationTaches_view_data);

        $this->viewState->set('scope.etatRealisationTache.formateur_id', $id);
        

        $etatRealisationTacheService =  new EtatRealisationTacheService();
        $etatRealisationTaches_view_data = $etatRealisationTacheService->prepareDataForIndexView();
        extract($etatRealisationTaches_view_data);

        $this->viewState->set('scope.etatChapitre.formateur_id', $id);
        

        $etatChapitreService =  new EtatChapitreService();
        $etatChapitres_view_data = $etatChapitreService->prepareDataForIndexView();
        extract($etatChapitres_view_data);

        $this->viewState->set('scope.etatFormation.formateur_id', $id);
        

        $etatFormationService =  new EtatFormationService();
        $etatFormations_view_data = $etatFormationService->prepareDataForIndexView();
        extract($etatFormations_view_data);

        $this->viewState->set('scope.labelRealisationTache.formateur_id', $id);
        

        $labelRealisationTacheService =  new LabelRealisationTacheService();
        $labelRealisationTaches_view_data = $labelRealisationTacheService->prepareDataForIndexView();
        extract($labelRealisationTaches_view_data);

        $this->viewState->set('scope.formation.formateur_id', $id);
        

        $formationService =  new FormationService();
        $formations_view_data = $formationService->prepareDataForIndexView();
        extract($formations_view_data);

        $this->viewState->set('scope.prioriteTache.formateur_id', $id);
        

        $prioriteTacheService =  new PrioriteTacheService();
        $prioriteTaches_view_data = $prioriteTacheService->prepareDataForIndexView();
        extract($prioriteTaches_view_data);

        if (request()->ajax()) {
            return view('PkgFormation::formateur._edit', array_merge(compact('itemFormateur','groupes', 'specialites', 'users'),$chapitre_compact_value, $commentaireRealisationTache_compact_value, $etatRealisationTache_compact_value, $etatChapitre_compact_value, $etatFormation_compact_value, $labelRealisationTache_compact_value, $formation_compact_value, $prioriteTache_compact_value));
        }

        return view('PkgFormation::formateur.edit', array_merge(compact('itemFormateur','groupes', 'specialites', 'users'),$chapitre_compact_value, $commentaireRealisationTache_compact_value, $etatRealisationTache_compact_value, $etatChapitre_compact_value, $etatFormation_compact_value, $labelRealisationTache_compact_value, $formation_compact_value, $prioriteTache_compact_value));


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