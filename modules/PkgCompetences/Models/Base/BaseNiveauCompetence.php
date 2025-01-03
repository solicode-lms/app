<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgCompetences\Models\Competence;

class BaseNiveauCompetence extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['nom', 'description', 'competence_id'];

    public function competence()
    {
        return $this->belongsTo(Competence::class, 'competence_id', 'id');
    }




    public function __toString()
    {
        return $this->nom;
    }
}
