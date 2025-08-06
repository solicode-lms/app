<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Imports\Base;

use Modules\PkgRealisationTache\Models\RealisationTache;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BaseRealisationTacheImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si un enregistrement avec la même référence existe.
     *
     * @param string $reference Référence unique de l'enregistrement.
     * @return RealisationTache|null
     */
    private function findExistingRecord($reference): ?RealisationTache
    {
        if($reference == null) return null;
        return RealisationTache::where('reference', $reference)->first();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return RealisationTache|null
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
        return new RealisationTache([
             'tache_id' => $values[0] ?? null,
             'realisation_projet_id' => $values[1] ?? null,
             'dateDebut' => $values[2] ?? null,
             'dateFin' => $values[3] ?? null,
             'remarque_evaluateur' => $values[4] ?? null,
             'etat_realisation_tache_id' => $values[5] ?? null,
             'note' => $values[6] ?? null,
             'remarques_formateur' => $values[7] ?? null,
             'remarques_apprenant' => $values[8] ?? null,
             'reference' => $reference,
        ]);


    }
}