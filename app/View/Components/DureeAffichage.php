<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DureeAffichage extends Component
{
    public $heures;

    public function __construct($heures)
    {
        $this->heures = $heures;
    }

    public function render()
    {
        return view('components.duree-affichage');
    }
}
