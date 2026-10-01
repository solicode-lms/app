<?php


namespace Modules\PkgQcm\Models;

use Modules\PkgQcm\Models\Base\BaseQcm;
use Illuminate\Support\Facades\Auth;

class Qcm extends BaseQcm
{
    public function __toString()
    {
        $titre = $this->titre ?? "";
        
        if (Auth::check()) {
            if ($this->formateur && $this->formateur->user_id !== Auth::id()) {
                return $titre . " (" . $this->formateur . ")";
            }
        }
        
        return $titre;
    }
}
