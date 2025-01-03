<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgCompetences\Models\Appreciation;
use Modules\PkgCompetences\Models\Competence;
use Modules\PkgCompetences\Models\Technology;
use Modules\PkgCreationProjet\Models\Projet;

class BaseTransfertCompetence extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['description', 'projet_id', 'competence_id', 'appreciation_id'];

    public function appreciation()
    {
        return $this->belongsTo(Appreciation::class, 'appreciation_id', 'id');
    }
    public function competence()
    {
        return $this->belongsTo(Competence::class, 'competence_id', 'id');
    }
    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id', 'id');
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'technology_transfert_competence');
    }



    public function __toString()
    {
        return $this->id;
    }
}
