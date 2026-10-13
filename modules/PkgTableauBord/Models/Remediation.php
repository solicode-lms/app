<?php

namespace Modules\PkgTableauBord\Models;

use Modules\PkgTableauBord\Models\Base\BaseRemediation;

class Remediation extends BaseRemediation
{
    const TYPE_INDIVIDUELLE = 'INDIVIDUELLE';
    const TYPE_TRINOME = 'TRINOME_ENTRAIDE';
    const TYPE_TRINOME_SOUTIEN = 'TRINOME_SOUTIEN';
    const TYPE_ATELIER_SOUTIEN = 'ATELIER_SOUTIEN';
    const TYPE_ENVIRONNEMENT_DEV = 'ENVIRONNEMENT_DEV';

    public static function getTypesValides(): array
    {
        return [
            self::TYPE_INDIVIDUELLE,
            self::TYPE_TRINOME,
            self::TYPE_TRINOME_SOUTIEN,
            self::TYPE_ATELIER_SOUTIEN,
            self::TYPE_ENVIRONNEMENT_DEV,
        ];
    }

    protected $casts = [
        'est_resolu' => 'boolean',
    ];

    public function generateReference(): string
    {
        return 'REM_' . ($this->realisation_tache_id ?? 0) . '_' . date('Ymd') . '_' . uniqid();
    }
}
