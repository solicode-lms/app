<?php


namespace Modules\PkgRealisationProjets\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Jobs\TraitementCrudJob;
use Modules\PkgRealisationProjets\App\Exports\AffectationProjetExport;
use Modules\PkgRealisationProjets\App\Exports\RealisationProjetsPV;
use Modules\PkgRealisationProjets\App\Exports\RealisationProjetExport;
use Modules\PkgRealisationProjets\App\Requests\AffectationProjetRequest;
use Modules\PkgRealisationProjets\Controllers\Base\BaseAffectationProjetController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AffectationProjetController extends BaseAffectationProjetController
{


    // public function store(AffectationProjetRequest $request) {
        
    //     // Augment le temps d'execution : à 2min, pardéfaut : 30 s
    //     // il faut de temps pour la création des RealisationChapitre
    //     ini_set('max_execution_time', 120); // en secondes
    //     parent::store($request);
         
    // }
   
    /**
     * @DynamicPermissionIgnore
     */
    public function getDataHasEvaluateurs(Request $request)
    {

        $this->authorizeAction('getData');


        $filter = $request->query('filter');
        $value = $request->query('value');
    
        if (!$filter || !$value) {
            return response()->json(['errors' => 'getData : Les paramètres "filter" et "value" sont requis'], 400);
        }
    
        // Récupération des tâches filtrées
        $taches = $this->service->getDataHasEvaluateurs($filter, $value);
    
        // Retourner tous les champs avec un champ `toString`
        return response()->json($taches->map(fn($tache) => array_merge(
            $tache->toArray(), // Convertir l'objet en tableau avec tous les champs
            ['toString' => $tache->__toString()] // Ajouter le champ `toString`
        )));
    }


    public function exportPV(Request $request, string $id, $format = 'xlsx')
    {
        $affectationProjet = $this->service->find($id);

        $realisationProjets_data = $affectationProjet->realisationProjets;


        // Nettoyer le titre pour le nom de fichier
        $titreProjet = $affectationProjet->projet->titre;
        $titreProjetClean = Str::slug($titreProjet, '_'); // transforme en "Nom_du_projet"

        $fileName = 'Realisation_Projet_' . $titreProjetClean . '_PV';

       
        // Sélection du format de téléchargement
        if ($format === 'csv') {
            return Excel::download(
                new RealisationProjetsPV ($realisationProjets_data, 'csv'),
                $fileName . '.csv',
                \Maatwebsite\Excel\Excel::CSV,
                ['Content-Type' => 'text/csv']
            );
        } elseif ($format === 'xlsx') {
            return Excel::download(
                new RealisationProjetsPV($realisationProjets_data, 'xlsx'),
                $fileName . '.xlsx',
                \Maatwebsite\Excel\Excel::XLSX
            );
        } else {
            return response()->json(['error' => 'Format non supporté'], 400);
        }
    }

    public function create()
    {
        // ownedByUser
        if (\Illuminate\Support\Facades\Auth::user()->hasRole('formateur')) {
            $this->viewState->set('scope_form.affectationProjet.projet.formateur_id', $this->sessionState->get('formateur_id'));
        }

        // scopeDataByRole
        if (\Illuminate\Support\Facades\Auth::user()->hasRole('formateur')) {
            $this->viewState->set('scope.projet.formateur_id', $this->sessionState->get('formateur_id'));
            $this->viewState->set('scope.groupe.formateurs.formateur_id', $this->sessionState->get('formateur_id'));
        }
        
        $itemAffectationProjet = $this->affectationProjetService->createInstance();

        $projets = $this->projetService->all();
        $groupes = $this->groupeService->all();

        // --- Filtrage des groupes ---
        if ($itemAffectationProjet->projet_id) {
            $projet = \Modules\PkgCreationProjet\Models\Projet::find($itemAffectationProjet->projet_id);
            
            if ($projet && $projet->filiere_id) {
                // Ne garder que les groupes de la même filière
                $groupes = $groupes->filter(function($groupe) use ($projet) {
                    return $groupe->filiere_id == $projet->filiere_id;
                });
            }

            // Exclure les groupes qui ont déjà ce projet affecté
            $assignedGroupIds = \Modules\PkgRealisationProjets\Models\AffectationProjet::where('projet_id', $itemAffectationProjet->projet_id)
                ->pluck('groupe_id')
                ->toArray();
                
            $groupes = $groupes->reject(function($groupe) use ($assignedGroupIds) {
                return in_array($groupe->id, $assignedGroupIds);
            });
        }
        // -----------------------------

        $sousGroupes = $this->sousGroupeService->all();
        $anneeFormations = $this->anneeFormationService->all();
        $evaluateurs = $this->evaluateurService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgRealisationProjets::affectationProjet._fields', compact('bulkEdit', 'itemAffectationProjet', 'projets', 'groupes', 'sousGroupes', 'anneeFormations', 'evaluateurs'));
        }
        return view('PkgRealisationProjets::affectationProjet.create', compact('bulkEdit', 'itemAffectationProjet', 'projets', 'groupes', 'sousGroupes', 'anneeFormations', 'evaluateurs'));
    }

    public function edit(string $id)
    {
        $itemAffectationProjet = $this->affectationProjetService->find($id);

        $projets = $this->projetService->all();
        $groupes = $this->groupeService->all();

        // --- Filtrage des groupes ---
        if ($itemAffectationProjet->projet_id) {
            $projet = \Modules\PkgCreationProjet\Models\Projet::find($itemAffectationProjet->projet_id);
            
            if ($projet && $projet->filiere_id) {
                // Ne garder que les groupes de la même filière
                $groupes = $groupes->filter(function($groupe) use ($projet) {
                    return $groupe->filiere_id == $projet->filiere_id;
                });
            }

            // Exclure les groupes qui ont déjà ce projet affecté (sauf le groupe actuellement affecté)
            $assignedGroupIds = \Modules\PkgRealisationProjets\Models\AffectationProjet::where('projet_id', $itemAffectationProjet->projet_id)
                ->where('id', '!=', $itemAffectationProjet->id)
                ->pluck('groupe_id')
                ->toArray();
                
            $groupes = $groupes->reject(function($groupe) use ($assignedGroupIds) {
                return in_array($groupe->id, $assignedGroupIds);
            });
        }
        // -----------------------------

        $sousGroupes = $this->sousGroupeService->all();
        $anneeFormations = $this->anneeFormationService->all();
        $evaluateurs = $this->evaluateurService->all();

        $bulkEdit = false;
        if (request()->ajax()) {
            return view('PkgRealisationProjets::affectationProjet._fields', compact('bulkEdit', 'itemAffectationProjet', 'projets', 'groupes', 'sousGroupes', 'anneeFormations', 'evaluateurs'));
        }
        return view('PkgRealisationProjets::affectationProjet.edit', compact('bulkEdit', 'itemAffectationProjet', 'projets', 'groupes', 'sousGroupes', 'anneeFormations', 'evaluateurs'));
    }
}
