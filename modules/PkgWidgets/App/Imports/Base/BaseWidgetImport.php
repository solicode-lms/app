<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Imports\Base;

use Modules\PkgWidgets\Models\Widget;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BaseWidgetImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si un enregistrement avec la même référence existe.
     *
     * @param string $reference Référence unique de l'enregistrement.
     * @return Widget|null
     */
    private function findExistingRecord($reference): ?Widget
    {
        if($reference == null) return null;
        return Widget::where('reference', $reference)->first();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return Widget|null
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
        return new Widget([
             'name' => $values[0] ?? null,
             'label' => $values[1] ?? null,
             'model_id' => $values[2] ?? null,
             'type_id' => $values[3] ?? null,
             'operation_id' => $values[4] ?? null,
             'color' => $values[5] ?? null,
             'icon' => $values[6] ?? null,
             'reference' => $reference,
             'parameters' => $values[8] ?? null,
        ]);


    }
}