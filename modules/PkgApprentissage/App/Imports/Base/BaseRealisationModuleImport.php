<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Imports\Base;

use Modules\PkgApprentissage\Models\RealisationModule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BaseRealisationModuleImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si un enregistrement avec la même référence existe.
     *
     * @param string $reference Référence unique de l'enregistrement.
     * @return RealisationModule|null
     */
    private function findExistingRecord($reference): ?RealisationModule
    {
        if($reference == null) return null;
        return RealisationModule::where('reference', $reference)->first();
    }

    /**
     * TODO : Il faut importer ManyToOne et ManyToMany By reference
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return RealisationModule|null
     */
    public function model(array $row)
    {
        // Convertir en tableau indexé pour gérer les colonnes par position
        $values = array_values($row);
        $reference = $values[5] ?? null; // La colonne "reference"


        // Vérifier si l'enregistrement existe
        $existingRecord = $this->findExistingRecord($reference);

        if ($existingRecord) {
            // Mise à jour de l'enregistrement existant
            $existingRecord->update([
                'nom' => $values[0] ?? $existingRecord->nom,
                'noteMin' => $values[1] ?? $existingRecord->noteMin,
                'noteMax' => $values[2] ?? $existingRecord->noteMax,
                'formateur_id' => $values[3] ?? $existingRecord->formateur_id,
                'description' => $values[4] ?? $existingRecord->description,
            ]);

            Log::info("Mise à jour réussie pour la référence : {$reference}");
            return null; // Retourner null pour éviter la création d'un doublon
        }

        // Création d'un nouvel enregistrement
        return new RealisationModule([
             'module_id' => $values[0] ?? null,
             'apprenant_id' => $values[1] ?? null,
             'progression_cache' => $values[2] ?? null,
             'etat_realisation_module_id' => $values[3] ?? null,
             'note_cache' => $values[4] ?? null,
             'bareme_cache' => $values[5] ?? null,
             'dernier_update' => $values[6] ?? null,
             'commentaire_formateur' => $values[7] ?? null,
             'date_debut' => $values[8] ?? null,
             'date_fin' => $values[9] ?? null,
             'reference' => $reference,
             'progression_ideal_cache' => $values[11] ?? null,
             'taux_rythme_cache' => $values[12] ?? null,
        ]);


    }
}