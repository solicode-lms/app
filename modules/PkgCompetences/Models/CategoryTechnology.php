<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgCompetences\Models\Technology;

class CategoryTechnology extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description'];




    public function technologies()
    {
        return $this->hasMany(Technology::class, 'categoryTechnology_id', 'id');
    }

    public function __toString()
    {
        return $this->nom;
    }
}
