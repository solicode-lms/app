<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Models;
use Modules\PkgWidgets\Models\Base\BaseWidget;

class Widget extends BaseWidget
{
    /**
     * Ajoute des attributs non enregistrés en base de données.
     *
     * @var array
     */
    protected $appends = ['count', 'data'];

}
