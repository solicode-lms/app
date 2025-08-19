<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProgressionBar extends Component
{
    public float $progression;
    public float $progressionIdeal;
    public ?float $tauxRythme;

    /**
     * Create a new component instance.
     */
    public function __construct(
        float $progression = 0,
        float $progressionIdeal = 0,
        ?float $tauxRythme = null
    ) {
        $this->progression = $progression;
        $this->progressionIdeal = $progressionIdeal;
        $this->tauxRythme = $tauxRythme;
    }

    public function render()
    {
        return view('components.progression-bar');
    }
}
