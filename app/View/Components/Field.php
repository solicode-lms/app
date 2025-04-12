<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

/**
 * Composant Blade permettant d'afficher dynamiquement une cellule (<td>) en fonction
 * d'un modèle Eloquent, d'un champ, et d'un partial optionnel.
 * Si aucun partial n'est fourni, il est généré automatiquement selon la convention :
 * "<package>::<model>.custom.fields.<field>"
 * Le slot par défaut est utilisé si aucun fichier de vue n'est trouvé.
 */
class Field extends Component
{
    public Model $data;
    public string $field;
    public ?string $partial;

    public function __construct(Model $data, string $field, string $partial = null)
    {
        $this->data = $data;
        $this->field = $field;
        $this->partial = $partial;
    }

    public function render()
    {
        $partial = $this->partial;

        if (!$partial) {
            $modelClass = get_class($this->data); // Ex: Modules\PkgGestionTaches\Models\RealisationTache
            $parts = explode('\\', $modelClass);

            $package = $parts[1] ?? 'PkgCore'; // PkgGestionTaches
            $model   = class_basename($modelClass); // RealisationTache

            $partial = $package . '::' . lcfirst($model) . '.custom.fields.' . $this->field;
        }

        return function (array $viewData) use ($partial) {
            $default = trim($viewData['slot']);
            $context = [
                'entity' => $this->data,
                'default' => $default
            ];

            if (View::exists($partial)) {
                return view($partial, $context)->render();
            }

            return $default;
        };
    }
}
