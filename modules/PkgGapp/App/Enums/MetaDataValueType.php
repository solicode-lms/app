<?php

namespace Modules\PkgGapp\App\Enums;

enum MetaDataValueType: string
{
    case BOOLEAN = 'BOOLEAN';
    case STRING = 'STRING';
    case INTEGER = 'INTEGER';
    case OBJECT = 'OBJECT';
}