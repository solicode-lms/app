<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models;
use Modules\PkgCompetences\Models\Base\BaseTechnology;

class Technology extends BaseTechnology
{

     protected $with = [
       'categoryTechnology'
    ];

}
