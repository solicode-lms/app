<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgCreationProjet\Models\NatureLivrable;
use Modules\PkgCreationProjet\Models\Projet;

class BaseLivrable extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['titre', 'nature_livrable_id', 'projet_id', 'description'];

    public function natureLivrable()
    {
        return $this->belongsTo(NatureLivrable::class, 'nature_livrable_id', 'id');
    }
    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id', 'id');
    }




    public function __toString()
    {
        return $this->titre;
    }
}
