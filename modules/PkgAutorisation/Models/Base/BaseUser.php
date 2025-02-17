<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgAutorisation\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgAutorisation\Models\Profile;

/**
 * Classe BaseUser
 * Cette classe sert de base pour le modèle User.
 */
class BaseUser extends BaseModel
{
    use HasFactory, HasDynamicContext;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes); 
        $this->isOwnedByUser =  false;

    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'email_verified_at', 'password', 'must_change_password', 'remember_token'
    ];
    public $manyToMany = [
        'Role' => ['relation' => 'roles' , "foreign_key" => "role_id" ]
    ];

       




    /**
     * Relation ManyToMany pour Roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles');
    }

    /**
     * Relation HasMany pour Users.
     *
     * @return HasMany
     */
    public function apprenants(): HasMany
    {
        return $this->hasMany(Apprenant::class, 'user_id', 'id');
    }
    /**
     * Relation HasMany pour Users.
     *
     * @return HasMany
     */
    public function formateurs(): HasMany
    {
        return $this->hasMany(Formateur::class, 'user_id', 'id');
    }
    /**
     * Relation HasMany pour Users.
     *
     * @return HasMany
     */
    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class, 'user_id', 'id');
    }



    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name ?? "";
    }
}
