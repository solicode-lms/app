<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Imports\Base;

use Carbon\Carbon;
use Modules\Core\Models\SysController;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseSysControllerImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return SysController::where('name', $row['name'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <SysController|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new SysController([
            'sys_module_id' => $row['sys_module_id'],
            'name' => $row['name'],
            'slug' => $row['slug'],
            'description' => $row['description'],
            'is_active' => $row['is_active'],
            'reference' => $row['reference'],
        ]);
    }
}
