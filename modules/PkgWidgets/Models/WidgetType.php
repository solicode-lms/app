<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetType extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'description'];



    public function __toString()
    {
        return $this->type;
    }

}
