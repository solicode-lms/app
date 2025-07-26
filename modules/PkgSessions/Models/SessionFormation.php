<?php


namespace Modules\PkgSessions\Models;
use Illuminate\Support\Str;
use Modules\PkgSessions\Models\Base\BaseSessionFormation;

class SessionFormation extends BaseSessionFormation
{
    public function generateReference(): string
    {
        // Générer un slug depuis titre
        $slug = Str::slug($this->titre, '-');

        // Limiter à 200 caractères maximum
        return Str::limit($slug, 200, '');
    }

     public function __toString()
    {
        return ($this->ordre ?? "") . " " . ($this->titre ?? "");
    }

}
