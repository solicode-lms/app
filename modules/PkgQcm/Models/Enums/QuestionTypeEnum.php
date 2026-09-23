<?php

namespace Modules\PkgQcm\Models\Enums;

enum QuestionTypeEnum: string
{
    case CHOIX_UNIQUE = 'choix_unique';
    case CHOIX_MULTIPLE = 'choix_multiple';
    case VRAI_FAUX = 'vrai_faux';

    public function label(): string
    {
        return match($this) {
            self::CHOIX_UNIQUE => 'Choix Unique',
            self::CHOIX_MULTIPLE => 'Choix Multiple',
            self::VRAI_FAUX => 'Vrai/Faux',
        };
    }
}
