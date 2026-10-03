<?php


namespace Modules\PkgSessions\Models;
use Illuminate\Support\Str;
use Modules\PkgSessions\Models\Base\BaseSessionFormation;

class SessionFormation extends BaseSessionFormation
{
     public function generateReference(): string
    {
        return  $this->filiere->reference . '-' . $this->code ;
    }

    public static $prefixFiliere = null;
    public static $filiereCodesCache = [];

    public function __toString()
    {
        $titre = ($this->ordre ?? "") . "-" . ($this->titre ?? "");

        if (self::$prefixFiliere === null && \Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user->hasRole('admin')) {
                self::$prefixFiliere = true;
            } elseif ($user->hasRole('formateur')) {
                $formateur = \Modules\PkgFormation\Models\Formateur::where('user_id', $user->id)->first();
                if ($formateur && $formateur->groupes()->distinct('filiere_id')->count('filiere_id') > 1) {
                    self::$prefixFiliere = true;
                } else {
                    self::$prefixFiliere = false;
                }
            } else {
                self::$prefixFiliere = false;
            }
        }

        if (self::$prefixFiliere && $this->filiere_id) {
            if (!isset(self::$filiereCodesCache[$this->filiere_id])) {
                $filiere = \Modules\PkgFormation\Models\Filiere::find($this->filiere_id);
                self::$filiereCodesCache[$this->filiere_id] = $filiere ? $filiere->code : "";
            }
            if (!empty(self::$filiereCodesCache[$this->filiere_id])) {
                return self::$filiereCodesCache[$this->filiere_id] . " - " . $titre;
            }
        }

        return $titre;
    }

}
