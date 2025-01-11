<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Imports\Base;

use Carbon\Carbon;
use Modules\PkgWidgets\Models\Widget;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BaseWidgetImport implements ToModel, WithHeadingRow
{
    /**
     * Vérifie si une tâche avec les mêmes attributs existe déjà dans la base de données.
     *
     * @param array $row Ligne de données importée.
     * @return bool
     */
    private function recordExists(array $row): bool
    {
        return Widget::where('label', $row['label'])->exists();
    }

    /**
     * Crée ou met à jour un enregistrement à partir des données importées.
     *
     * @param array $row Ligne de données importée.
     * @return <Widget|null
     */
    public function model(array $row)
    {
        if ($this->recordExists($row)) {
            return null; // Enregistrement existant, aucune action
        }

        // Crée un nouvel enregistrement à partir des données importées
        return new Widget([
            'color' => $row['color'],
            'icon' => $row['icon'],
            'label' => $row['label'],
            'model_id' => $row['model_id'],
            'name' => $row['name'],
            'operation_id' => $row['operation_id'],
            'parameters' => $row['parameters'],
            'type_id' => $row['type_id'],
        ]);
    }
}
