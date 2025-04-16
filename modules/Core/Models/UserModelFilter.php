<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models;
use Modules\Core\Models\Base\BaseUserModelFilter;

class UserModelFilter extends BaseUserModelFilter
{
    // Convertire filters to arrays
    protected $casts = [
        'filters' => 'array',
    ];
}
