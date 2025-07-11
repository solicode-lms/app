<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

/**
 * Composant Blade permettant d'afficher dynamiquement un bouton d'action CRUD (<td>).
 * Si un partial existe, il sera utilisé, sinon un bouton par défaut sera affiché.
 */
class ActionButton extends Component
{
    public Model $entity;
    public string $actionName;
    public ?string $partial;

    public function __construct(Model $entity, string $actionName, string $partial = null)
    {
        $this->entity = $entity;
        $this->actionName = $actionName;
        $this->partial = $partial;
    }

    public function render()
    {
        $partial = $this->partial;

        if (!$partial) {
            $modelClass = get_class($this->entity); // Ex: Modules\PkgRealisationTache\Models\RealisationTache
            $parts = explode('\\', $modelClass);

            $package = $parts[1] ?? 'PkgCore'; // Exemple : PkgRealisationTache
            $model = class_basename($modelClass); // Exemple : RealisationTache

            $partial = $package . '::' . lcfirst($model) . '.custom.actions.' . $this->actionName;
            // => ex : PkgRealisationTache::realisationTache.custom.actions.edit
        }

        return function (array $viewData) use ($partial) {
            $default = trim($viewData['slot']);
            $context = [
                'entity' => $this->entity,
                'default' => $default,
                'actionName' => $this->actionName,
            ];

            if (View::exists($partial)) {
                return view($partial, $context)->render();
            }

            return $default;
        };
    }
}
