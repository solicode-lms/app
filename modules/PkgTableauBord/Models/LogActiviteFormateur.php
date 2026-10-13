<?php

namespace Modules\PkgTableauBord\Models;

use Modules\PkgTableauBord\Models\Base\BaseLogActiviteFormateur;

class LogActiviteFormateur extends BaseLogActiviteFormateur
{
    const TYPE_PILOTAGE_KANBAN = 'PILOTAGE_KANBAN';
    const TYPE_GESTION_FILE_FIFO = 'GESTION_FILE_FIFO';
    const TYPE_EXPORT_BORDEREAU = 'EXPORT_BORDEREAU';

    public static function getTypesValides(): array
    {
        return [
            self::TYPE_PILOTAGE_KANBAN,
            self::TYPE_GESTION_FILE_FIFO,
            self::TYPE_EXPORT_BORDEREAU,
        ];
    }

    protected $casts = [
        'date_activite' => 'date',
        'duree_secondes' => 'integer',
    ];

    public function generateReference(): string
    {
        return 'LOG_ACT_' . $this->formateur_id . '_' . date('Ymd') . '_' . ($this->type_activite ?? 'ACT') . '_' . uniqid();
    }
}
