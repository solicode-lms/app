<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgUtilisateurs\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasDynamicContext;

class BaseApprenantKonosy extends Model
{
    use HasFactory, HasDynamicContext;

    protected $fillable = ['MatriculeEtudiant', 'Nom', 'Prenom', 'Sexe', 'EtudiantActif', 'Diplome', 'Principale', 'LibelleLong', 'CodeDiplome', 'DateNaissance', 'DateInscription', 'LieuNaissance', 'CIN', 'NTelephone', 'Adresse', 'Nationalite', 'Nom_Arabe', 'Prenom_Arabe', 'NiveauScolaire'];





    public function __toString()
    {
        return $this->Nom;
    }
}
