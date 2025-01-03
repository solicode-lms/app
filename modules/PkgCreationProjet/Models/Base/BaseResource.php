<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgCreationProjet\Models\Projet;

class BaseResource extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['nom', 'lien', 'description', 'projet_id'];

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id', 'id');
    }




    public function __toString()
    {
        return $this->nom;
    }
}
