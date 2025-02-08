<?php

namespace Modules\PkgAutorisation\Models;

use App\Traits\HasReference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\PkgFormation\Models\Formateur;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Modules\Core\Services\SessionState;

class User extends Authenticatable
{

    use HasFactory, Notifiable, HasRoles, HasReference;

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
        'reference',
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
    
    /**
     * Gapp détecter la relation OneToOne comme ManyToOne
     *
     * @return HasMany
     */
    public function formateurs(): HasMany
    {
        return $this->hasMany(Formateur::class, 'user_id', 'id');
    }

 
    public function getUsersContext()
    {
        $contextUsers = [];


        $sessionState = app(SessionState::class);
        $contextUsers['annee_formation_id'] = $sessionState->get("annee_formation_id");        
        $formateur = $this->formateur;
    
        // il doit être ajouter seulement si la gestion de l'entity is OwnedByUser
        // if ($formateur) {
        //     $contextUsers['formateur_id'] = $formateur->id;
        // }
    
        return $contextUsers;
    }

    // public function getUsersSessionContext()
    // {
    //     $contextUsers = [];


    //     $sessionState = app(SessionState::class);
    //     $contextUsers['annee_formation_id'] = $sessionState->get("annee_formation_id");        
    //     $formateur = $this->formateur;
    
    //     // il doit être ajouter seulement si la gestion de l'entity is OwnedByUser
    //     // if ($formateur) {
    //     //     $contextUsers['formateur_id'] = $formateur->id;
    //     // }
    
    //     return $contextUsers;
    // }
    

    // TODO : ajouter ce code dans Gapp, pour une relation ManyToManyPolymorphique
    // Cette méthode est déja exist dans HasRoles
    // Définir la relation avec les rôles via morphique
    //    public function roles()
    //    {
    //        return $this->morphToMany(Role::class, 'model', 'model_has_roles', 'model_id', 'role_id');
    //    }

}
