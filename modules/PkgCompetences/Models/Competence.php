<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgCompetences\Models\Module;
use Modules\PkgCompetences\Models\Technology;

class Competence extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'description', 'module_id'];

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'competence_technology');
    }

    public function __toString()
    {
        return $this->code;
    }

}
