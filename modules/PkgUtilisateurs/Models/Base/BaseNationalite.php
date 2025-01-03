<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;
use Modules\PkgUtilisateurs\Models\Apprenant;

class BaseNationalite extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['code', 'nom', 'description'];




    public function apprenants()
    {
        return $this->hasMany(Apprenant::class, 'nationalite_id', 'id');
    }

    public function __toString()
    {
        return $this->code;
    }
}
