<?php
namespace Modules\PkgCreationProjet\Models;
use Modules\PkgCreationProjet\Models\Base\BaseEquipeProjet;

class EquipeProjet extends BaseEquipeProjet
{
    protected $with = ['sysColor'];
}
