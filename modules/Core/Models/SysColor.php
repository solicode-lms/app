<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysColor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'hex'];



    public function __toString()
    {
        return $this->name;
    }

}
