<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgCompetences\Models\CategoryTechnology;
use Modules\PkgCompetences\Models\Competence;
use Modules\PkgCreationProjet\Models\TransfertCompetence;

class Technology extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['nom', 'description', 'category_technology_id'];

    public function categoryTechnology()
    {
        return $this->belongsTo(CategoryTechnology::class, 'category_technology_id', 'id');
    }

    public function competences()
    {
        return $this->belongsToMany(Competence::class, 'competence_technology');
    }
    public function transfertCompetences()
    {
        return $this->belongsToMany(TransfertCompetence::class, 'technology_transfert_competence');
    }



    public function __toString()
    {
        return $this->nom;
    }
}
