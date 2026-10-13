<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgTableauBord\App\Imports\Base;

use Modules\PkgTableauBord\Models\BordereauxOfficiel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BaseBordereauxOfficielImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si un enregistrement avec la même référence existe.
     *
     * @param string $reference Référence unique de l'enregistrement.
     * @return BordereauxOfficiel|null
     */
    private function findExistingRecord($reference): ?BordereauxOfficiel
    {
        if($reference == null) return null;
        return BordereauxOfficiel::where('reference', $reference)->first();
    }

    /**
     * TODO : Il faut importer ManyToOne et ManyToMany By reference
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return BordereauxOfficiel|null
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
        return new BordereauxOfficiel([
             'reference' => $reference,
             'module_id' => $values[1] ?? null,
             'groupe_id' => $values[2] ?? null,
             'annee_formation_id' => $values[3] ?? null,
             'nb_apprenants' => $values[4] ?? null,
             'taux_conformite_calcul' => $values[5] ?? null,
             'statut' => $values[6] ?? null,
             'date_scellement' => $values[7] ?? null,
             'hash_signature' => $values[8] ?? null,
             'genere_par_user_id' => $values[9] ?? null,
        ]);


    }
}