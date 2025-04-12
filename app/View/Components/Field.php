<?php
namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;

class Field extends Component
{
    public $partial;
    public $data;

    public function __construct(string $partial, array $data = [])
    {
        $this->partial = $partial;
        $this->data = $data;
    }

    public function render()
    {
        return function (array $viewData) {
            $default = trim($viewData['slot']); // contenu du slot <td> par défaut
            $context = array_merge($this->data, ['td_value' => $default]);

            if (View::exists($this->partial)) {
                return view($this->partial, $context)->render();
            }

            return $default;
        };
    }
}
