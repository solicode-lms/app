<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgWidgets\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetOperation extends Model
{
    use HasFactory;

    protected $fillable = ['operation', 'description'];



    public function __toString()
    {
        return $this->operation;
    }

}
