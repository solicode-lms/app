<?php

namespace Modules\PkgGapp\App\Enums;

enum RelationshipType: string
{
    case ONE_TO_ONE = 'ONE_TO_ONE';
    case ONE_TO_MANY = 'ONE_TO_MANY';
    case MANY_TO_ONE = 'MANY_TO_ONE';
    case MANY_TO_MANY = 'MANY_TO_MANY';
}