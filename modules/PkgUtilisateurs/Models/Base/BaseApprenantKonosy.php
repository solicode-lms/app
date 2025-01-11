<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models\Base;

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

    public function __construct() {
        parent::__construct(); 
        $this->isOwnedByUser =  false;
    }

    
    /**
     * Les attributs remplissables pour le modèle.
     *
     * @var array
     */
    protected $fillable = [
        'Adresse', 'CIN', 'CodeDiplome', 'DateInscription', 'DateNaissance', 'Diplome', 'EtudiantActif', 'LibelleLong', 'LieuNaissance', 'MatriculeEtudiant', 'Nationalite', 'NiveauScolaire', 'Nom', 'Nom_Arabe', 'NTelephone', 'Prenom', 'Prenom_Arabe', 'Principale', 'Sexe'
    ];




    /**
     * Méthode __toString pour représenter le modèle sous forme de chaîne.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->Nom;
    }
}
