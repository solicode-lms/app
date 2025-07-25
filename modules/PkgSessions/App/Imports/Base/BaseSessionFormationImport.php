<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgSessions\App\Imports\Base;

use Modules\PkgSessions\Models\SessionFormation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BaseSessionFormationImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si un enregistrement avec la même référence existe.
     *
     * @param string $reference Référence unique de l'enregistrement.
     * @return SessionFormation|null
     */
    private function findExistingRecord($reference): ?SessionFormation
    {
        if($reference == null) return null;
        return SessionFormation::where('reference', $reference)->first();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return SessionFormation|null
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
        return new SessionFormation([
             'ordre' => $values[0] ?? null,
             'titre' => $values[1] ?? null,
             'date_debut' => $values[2] ?? null,
             'date_fin' => $values[3] ?? null,
             'jour_feries_vacances' => $values[4] ?? null,
             'thematique' => $values[5] ?? null,
             'objectifs_pedagogique' => $values[6] ?? null,
             'remarques' => $values[7] ?? null,
             'titre_prototype' => $values[8] ?? null,
             'description_prototype' => $values[9] ?? null,
             'contraintes_prototype' => $values[10] ?? null,
             'titre_projet' => $values[11] ?? null,
             'reference' => $reference,
             'description_projet' => $values[13] ?? null,
             'contraintes_projet' => $values[14] ?? null,
             'filiere_id' => $values[15] ?? null,
             'annee_formation_id' => $values[16] ?? null,
        ]);


    }
}