<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;

class Ville extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['nom'];





    public function __toString()
    {
        return $this->nom;
    }
}
