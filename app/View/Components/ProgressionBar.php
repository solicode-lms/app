<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProgressionBar extends Component
{
    public float $progression;
    public float $progressionIdeal;
    public ?float $tauxRythme;
    public float $pourcentageNonValide;
    public float $baremeNonEvalue;

    /**
     * Create a new component instance.
     */
    public function __construct(
        float $progression = 0,
        float $progressionIdeal = 0,
        ?float $tauxRythme = null,
        float $pourcentageNonValide = 0,
        float $baremeNonEvalue = 0
    ) {
        $this->progression = $progression;
        $this->progressionIdeal = $progressionIdeal;
        $this->tauxRythme = $tauxRythme;
        $this->pourcentageNonValide = $pourcentageNonValide;
        $this->baremeNonEvalue = $baremeNonEvalue;
    }

    public function render()
    {
        return view('components.progression-bar');
    }
}
