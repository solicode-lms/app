<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Badge extends Component
{
    public $text;
    public $background;
    public $color;

    /**
     * Crée une nouvelle instance du composant.
     *
     * @param string $text  Texte affiché dans le badge.
     * @param string|null $background Couleur de fond (HEX ou classe CSS Tailwind).
     * @param string|null $color Couleur du texte (par défaut blanc).
     */
    public function __construct($text, $background = null, $color = 'white')
    {
        $this->text = $text;
        $this->background = $background ?? '#6c757d'; // Couleur de fond par défaut (gris Bootstrap).
        $this->color = $color;
    }

    public function render()
    {
        return view('components.badge');
    }
}
