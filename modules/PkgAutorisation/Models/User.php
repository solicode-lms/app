<?php

namespace Modules\PkgAutorisation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\PkgUtilisateurs\Models\Formateur;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use HasFactory, Notifiable, HasRoles;

    public const ADMIN = "admin";
    public const MEMBRE = "membre";

    public const FORMATEUR = "formateur";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relation HasOne avec Formateur
    public function formateur()
    {
        return $this->hasOne(Formateur::class);
    }
    
    // HasRoles
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'model_has_roles');
    // }
}
