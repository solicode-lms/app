<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\App\Imports\Base;

use Modules\PkgApprenants\Models\ApprenantKonosy;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class BaseApprenantKonosyImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si un enregistrement avec la même référence existe.
     *
     * @param string $reference Référence unique de l'enregistrement.
     * @return ApprenantKonosy|null
     */
    private function findExistingRecord($reference): ?ApprenantKonosy
    {
        if($reference == null) return null;
        return ApprenantKonosy::where('reference', $reference)->first();
    }

    /**
     * TODO : Il faut importer ManyToOne et ManyToMany By reference
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return ApprenantKonosy|null
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
        return new ApprenantKonosy([
             'MatriculeEtudiant' => $values[0] ?? null,
             'Nom' => $values[1] ?? null,
             'Prenom' => $values[2] ?? null,
             'Sexe' => $values[3] ?? null,
             'EtudiantActif' => $values[4] ?? null,
             'Diplome' => $values[5] ?? null,
             'Principale' => $values[6] ?? null,
             'LibelleLong' => $values[7] ?? null,
             'CodeDiplome' => $values[8] ?? null,
             'DateNaissance' => $values[9] ?? null,
             'DateInscription' => $values[10] ?? null,
             'LieuNaissance' => $values[11] ?? null,
             'CIN' => $values[12] ?? null,
             'NTelephone' => $values[13] ?? null,
             'Adresse' => $values[14] ?? null,
             'Nationalite' => $values[15] ?? null,
             'Nom_Arabe' => $values[16] ?? null,
             'Prenom_Arabe' => $values[17] ?? null,
             'NiveauScolaire' => $values[18] ?? null,
             'reference' => $reference,
        ]);


    }
}