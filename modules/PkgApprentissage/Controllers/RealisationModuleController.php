<?php

namespace Modules\PkgApprentissage\Controllers;

use Modules\PkgApprentissage\Controllers\Base\BaseRealisationModuleController;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PkgApprentissage\App\Exports\RealisationModuleExport;
use Modules\PkgApprentissage\App\Exports\RealisationModuleCanevasExport;

class RealisationModuleController extends BaseRealisationModuleController
{
    public function index(Request $request)
    {
        $this->viewState->setContextKeyIfEmpty('realisationModule.index');
        // N'afficher que les réalisations des apprenants actifs
        $this->viewState->set('where.realisationModule.apprenant.actif', 1);

        return parent::index($request);
    }

  
    /**
     * @DynamicPermissionIgnore
     * Marque toutes les notifications de l'utilisateur courant comme lues.
     *  * Exporte un fichier canevas Excel pour la saisie des notes avec les colonnes CEF, Nom, Prénom, CC1, CC2, CC3, EFM.
     */
    public function exportCanevas(Request $request)
    {
        $this->viewState->setContextKeyIfEmpty('realisationModule.index');

        // Charger le dernier filtre sauvegardé en base de données
        $this->realisationModuleService->loadLastFilterIfEmpty();

        // Validation des filtres Module et Groupe
        $filterVariables = $this->viewState->getFilterVariables('realisationModule');
        $hasModule = !empty($filterVariables['module_id']);
        $hasGroupe = !empty($filterVariables['Apprenant.groupes.id']) || !empty($filterVariables['Apprenant_groupes_id']);

        if (!$hasModule || !$hasGroupe) {
            return redirect()->route('realisationModules.index')->with(
                'warning',
                'Veuillez choisir un Module et un Groupe dans les filtres de recherche avant d\'exporter le canevas.'
            );
        }

        // N'exporter que les réalisations des apprenants actifs
        $this->viewState->set('where.realisationModule.apprenant.actif', 1);

        $realisationModules_params = array_merge(
            ['search' => $request->get(
                'realisationModules_search',
                $this->viewState->get("filter.realisationModule.realisationModules_search")
            )],
            $request->except(['realisationModules_search', 'page'])
        );

        $realisationModules_data = $this->realisationModuleService->allQuery($realisationModules_params)->get();

        // Eager-load relations utiles
        $realisationModules_data->load([
            'apprenant',
            'realisationCompetences.realisationMicroCompetences.realisationUas.uniteApprentissage',
        ]);

        // Récupérer les informations pour nommer le fichier d'export
        $module = \Modules\PkgFormation\Models\Module::find($filterVariables['module_id']);
        $groupeId = $filterVariables['Apprenant.groupes.id'] ?? $filterVariables['Apprenant_groupes_id'] ?? null;
        $groupe = $groupeId ? \Modules\PkgApprenants\Models\Groupe::with('anneeFormation')->find($groupeId) : null;

        $moduleCode = $module ? $module->code : 'Module';
        $groupeCode = $groupe ? $groupe->code : 'Groupe';

        $filename = trim(implode('-', array_filter([$groupeCode, $moduleCode])));
        $filename = str_replace(['\\', '/', ':', '*', '?', '"', '<', '>', '|'], '_', $filename);

        return Excel::download(new RealisationModuleCanevasExport($realisationModules_data), $filename . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }


    public function export($format)
    {
        $this->viewState->setContextKeyIfEmpty('realisationModule.index');

        // Charger le dernier filtre sauvegardé en base de données
        $this->realisationModuleService->loadLastFilterIfEmpty();

        // Validation des filtres Module et Groupe
        $filterVariables = $this->viewState->getFilterVariables('realisationModule');
        $hasModule = !empty($filterVariables['module_id']);
        $hasGroupe = !empty($filterVariables['Apprenant.groupes.id']) || !empty($filterVariables['Apprenant_groupes_id']);

        if (!$hasModule || !$hasGroupe) {
            return redirect()->route('realisationModules.index')->with(
                'warning',
                'Veuillez choisir un Module et un Groupe dans les filtres de recherche avant d\'exporter.'
            );
        }

        // Vérification si le module comporte des Unités d'Apprentissage (UA)
        $module = \Modules\PkgFormation\Models\Module::find($filterVariables['module_id']);
        if ($module && !$module->isHaveUa) {
            return redirect()->route('realisationModules.index')->with(
                'warning',
                "L'export du PV est disponible uniquement pour les modules structurés en Unités d'Apprentissage (UA). Dans ce cas, le formateur peut l'extraire directement via l'affectation de projet du groupe, sous réserve que les notes d'évaluation aient été renseignées."
            );
        }

        // N'exporter que les réalisations des apprenants actifs
        $this->viewState->set('where.realisationModule.apprenant.actif', 1);

        $request = request();
        $realisationModules_params = array_merge(
            ['search' => $request->get(
                'realisationModules_search',
                $this->viewState->get("filter.realisationModule.realisationModules_search")
            )],
            $request->except(['realisationModules_search', 'page'])
        );

        $realisationModules_data = $this->realisationModuleService->allQuery($realisationModules_params)->get();

        // Récupérer les informations pour nommer le fichier d'export
        $module = \Modules\PkgFormation\Models\Module::find($filterVariables['module_id']);
        $groupeId = $filterVariables['Apprenant.groupes.id'] ?? $filterVariables['Apprenant_groupes_id'] ?? null;
        $groupe = $groupeId ? \Modules\PkgApprenants\Models\Groupe::with('anneeFormation')->find($groupeId) : null;

        $moduleNom = $module ? $module->nom : 'Module';
        $groupeNom = $groupe ? $groupe->code : 'Groupe';
        $anneeFormation = $groupe && $groupe->anneeFormation ? $groupe->anneeFormation->reference : '';

        $filename = trim(implode(' - ', array_filter([$moduleNom, $groupeNom, $anneeFormation])));
        $filename = str_replace(['\\', '/', ':', '*', '?', '"', '<', '>', '|'], '_', $filename);

        // Vérifier le format et exporter en conséquence
        if ($format === 'csv') {
            return Excel::download(new RealisationModuleExport($realisationModules_data, 'csv'), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV, ['Content-Type' => 'text/csv']);
        } elseif ($format === 'xlsx') {
            // Eager-load des relations UA pour les colonnes CC par UA
            $realisationModules_data->load([
                'realisationCompetences.realisationMicroCompetences.realisationUas.uniteApprentissage',
            ]);
            return Excel::download(new RealisationModuleExport($realisationModules_data, 'xlsx'), $filename . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        } else {
            return response()->json(['error' => 'Format non supporté'], 400);
        }
    }
}
