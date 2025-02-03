<?php

namespace Modules\PkgGapp\App\Enums;
enum MetadataTargetType: string
{
    case ATTRIBUTE = 'ATTRIBUTE';
    case MODEL = 'MODEL';
    case RELATIONSHIP = 'RELATIONSHIP';
}