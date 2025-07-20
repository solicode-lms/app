<?php
// User extends Authenticatable
namespace Modules\PkgAutorisation\Models;

use App\Traits\HasDynamicContext;
use App\Traits\HasReference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\PkgFormation\Models\Formateur;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Modules\Core\Services\SessionState;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgEvaluateurs\Models\Evaluateur;

class User extends Authenticatable
{

    use HasFactory, Notifiable, HasRoles, HasReference, HasDynamicContext;

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
        'must_change_password'
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

    // protected $with = [
    //    'formateur',
    //    'apprenant',
    //    'evaluateur'
    // ];

    // Relation HasOne avec Formateur
    public function formateur(): HasOne
    {
        return $this->hasOne(Formateur::class);
    }

    public function evaluateur(): HasOne
    {
        return $this->hasOne(Evaluateur::class);
    }

    public function apprenant(): HasOne
    {
        return $this->hasOne(Apprenant::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
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
    public function apprenants(): HasMany
    {
        return $this->hasMany(Apprenant::class, 'user_id', 'id');
    }
    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class, 'user_id', 'id');
    }
 
    public function getUsersContext()
    {
        $contextUsers = [];


        $sessionState = app(SessionState::class);
        $contextUsers['scope.global.annee_formation_id'] = $sessionState->get("annee_formation_id");        
        $formateur = $this->formateur;
    
        // il doit être ajouter seulement si la gestion de l'entity is OwnedByUser
        // if ($formateur) {
        //     $contextUsers['formateur_id'] = $formateur->id;
        // }
    
        return $contextUsers;
    }

   
    

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->profile()->create(); // Crée un profil vide lors de la création de l'utilisateur
        });
    }

    public function __toString()
    {
        return $this->email ?? "";
    }

    public function generateReference(): string
    {
        return $this->email ;
    }

}
