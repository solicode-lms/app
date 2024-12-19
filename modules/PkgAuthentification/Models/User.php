<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAuthentification\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'email_verified_at', 'password', 'remember_token'];



    public function __toString()
    {
        return $this->id;
    }

}
