<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niveaux_scolaire extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description'];



    public function __toString()
    {
        return $this->id;
    }

}
