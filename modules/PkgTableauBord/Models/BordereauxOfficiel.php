<?php

namespace Modules\PkgTableauBord\Models;

use Modules\PkgTableauBord\Models\Base\BaseBordereauxOfficiel;

class BordereauxOfficiel extends BaseBordereauxOfficiel
{
    const STATUT_VALIDE = 'VALIDE_SANS_ANOMALIE';
    const STATUT_DIVERGENCE = 'DIVERGENCE_DETECTEE';
    const STATUT_EN_COURS = 'EN_COURS';

    protected $casts = [
        'date_scellement' => 'datetime',
        'taux_conformite_calcul' => 'float',
    ];

    public function generateReference(): string
    {
        return 'BORD_' . ($this->groupe_id ?? 0) . '_M' . ($this->module_id ?? 0) . '_' . date('Ymd') . '_' . uniqid();
    }
}
