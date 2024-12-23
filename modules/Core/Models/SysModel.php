<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysModel extends Model
{
    use HasFactory;

    protected $fillable = ['model', 'description'];



    public function __toString()
    {
        return $this->id;
    }

}
