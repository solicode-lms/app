<?php

namespace Modules\PkgQcm\Models\Enums;

enum QuestionTypeEnum: string
{
    case CHOIX_UNIQUE = 'Choix unique';
    case CHOIX_MULTIPLE = 'Choix multiple';
    case VRAI_FAUX = 'Vrai/Faux';

    public function label(): string
    {
        return match ($this) {
            self::CHOIX_UNIQUE => 'Choix unique',
            self::CHOIX_MULTIPLE => 'Choix multiple',
            self::VRAI_FAUX => 'Vrai / Faux',
        };
    }
}
