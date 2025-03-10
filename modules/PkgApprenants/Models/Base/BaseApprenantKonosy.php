<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgApprenants\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\OwnedByUser;
use App\Traits\HasDynamicContext;
use Modules\Core\Models\BaseModel;


/**
 * Classe BaseApprenantKonosy
 * Cette classe sert de base pour le modèle ApprenantKonosy.
 */
class BaseApprenantKonosy extends BaseModel
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
        'MatriculeEtudiant', 'Nom', 'Prenom', 'Sexe', 'EtudiantActif', 'Diplome', 'Principale', 'LibelleLong', 'CodeDiplome', 'DateNaissance', 'DateInscription', 'LieuNaissance', 'CIN', 'NTelephone', 'Adresse', 'Nationalite', 'Nom_Arabe', 'Prenom_Arabe', 'NiveauScolaire'
    ];







    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->Nom ?? "";
    }
}
