<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgCompetences\Models\Module;
use Modules\PkgCompetences\Models\NiveauCompetence;
use Modules\PkgCompetences\Models\Technology;
use Modules\PkgCreationProjet\Models\TransfertCompetence;

class BaseCompetence extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['code', 'nom', 'description', 'module_id'];

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'competence_technology');
    }


    public function niveauCompetences()
    {
        return $this->hasMany(NiveauCompetence::class, 'competence_id', 'id');
    }
    public function transfertCompetences()
    {
        return $this->hasMany(TransfertCompetence::class, 'competence_id', 'id');
    }

    public function __toString()
    {
        return $this->code;
    }
}
