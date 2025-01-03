<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCreationProjet\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgCreationProjet\Models\Livrable;

class BaseNatureLivrable extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['nom', 'description'];




    public function livrables()
    {
        return $this->hasMany(Livrable::class, 'natureLivrable_id', 'id');
    }

    public function __toString()
    {
        return $this->nom;
    }
}
