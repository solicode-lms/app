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

     public function __toString()
    {
        return ($this->ordre ?? "") . "-" . ($this->titre ?? "");
    }

}
