<?php
 

namespace Modules\PkgWidgets\Models;
use Modules\PkgWidgets\Models\Base\BaseWidgetUtilisateur;

class WidgetUtilisateur extends BaseWidgetUtilisateur
{
 protected $with = [
        'user',
    ];

}
