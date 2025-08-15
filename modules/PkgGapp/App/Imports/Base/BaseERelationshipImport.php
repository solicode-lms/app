<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Imports\Base;

use Modules\PkgGapp\Models\ERelationship;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BaseERelationshipImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si un enregistrement avec la même référence existe.
     *
     * @param string $reference Référence unique de l'enregistrement.
     * @return ERelationship|null
     */
    private function findExistingRecord($reference): ?ERelationship
    {
        if($reference == null) return null;
        return ERelationship::where('reference', $reference)->first();
    }

    /**
     * TODO : Il faut importer ManyToOne et ManyToMany By reference
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return ERelationship|null
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
        return new ERelationship([
             'reference' => $reference,
             'name' => $values[1] ?? null,
             'type' => $values[2] ?? null,
             'source_e_model_id' => $values[3] ?? null,
             'target_e_model_id' => $values[4] ?? null,
             'cascade_on_delete' => $values[5] ?? null,
             'is_cascade' => $values[6] ?? null,
             'description' => $values[7] ?? null,
             'column_name' => $values[8] ?? null,
             'referenced_table' => $values[9] ?? null,
             'referenced_column' => $values[10] ?? null,
             'through' => $values[11] ?? null,
             'with_column' => $values[12] ?? null,
             'morph_name' => $values[13] ?? null,
        ]);


    }
}