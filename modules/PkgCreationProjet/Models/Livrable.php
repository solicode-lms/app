<?php

namespace Modules\PkgCreationProjet\Models;

use App\Traits\HasDynamicContext;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\PkgCreationProjet\Models\NatureLivrable;
use Modules\PkgCreationProjet\Models\Projet;

class Livrable extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['titre', 'description', 'projet_id', 'nature_livrable_id'];

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
