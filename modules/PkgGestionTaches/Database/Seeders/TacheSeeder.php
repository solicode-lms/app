<?php

namespace Modules\PkgGestionTaches\Database\Seeders;

use Modules\PkgGestionTaches\Database\Seeders\Base\BaseTacheSeeder;

class TacheSeeder extends BaseTacheSeeder
{
  
    protected array $featurePermissions = [
        'Afficher' => ['show'],
        'Lecture' => ['index', 'show'],
        'Édition sans Ajouter' => ['index', 'show', 'edit', 'update', 'dataCalcul'],
        'Édition' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy', 'dataCalcul'],
        'Extraction' => ['import', 'export'],
    ];
}
