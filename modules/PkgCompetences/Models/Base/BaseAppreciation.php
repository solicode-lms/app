<?php


namespace Modules\PkgCompetences\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use App\Traits\OwnedByUser;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\PkgCreationProjet\Models\TransfertCompetence;
use Modules\PkgUtilisateurs\Models\Formateur;

class BaseAppreciation extends Model
{
    use HasFactory, HasDynamicContext,OwnedByUser;

    protected $fillable = ['nom', 'description', 'noteMin', 'noteMax', 'formateur_id'];

    public function formateur(): BelongsTo
    {
        return $this->belongsTo(Formateur::class, 'formateur_id', 'id');
    }



    public function transfertCompetences():HasMany
    {
        return $this->hasMany(TransfertCompetence::class, 'appreciation_id', 'id');
    }

    public function __toString()
    {
        return $this->nom;
    }
}
