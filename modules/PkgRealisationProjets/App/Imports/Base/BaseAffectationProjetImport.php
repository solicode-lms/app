<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Imports\Base;

use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BaseAffectationProjetImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si un enregistrement avec la même référence existe.
     *
     * @param string $reference Référence unique de l'enregistrement.
     * @return AffectationProjet|null
     */
    private function findExistingRecord($reference): ?AffectationProjet
    {
        if($reference == null) return null;
        return AffectationProjet::where('reference', $reference)->first();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return AffectationProjet|null
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
        return new AffectationProjet([
             'projet_id' => $values[0] ?? null,
             'groupe_id' => $values[1] ?? null,
             'annee_formation_id' => $values[2] ?? null,
             'date_debut' => $values[3] ?? null,
             'date_fin' => $values[4] ?? null,
             'sous_groupe_id' => $values[5] ?? null,
             'is_formateur_evaluateur' => $values[6] ?? null,
             'reference' => $reference,
             'bareme_arrondi' => $values[8] ?? null,
             'description' => $values[9] ?? null,
        ]);


    }
}