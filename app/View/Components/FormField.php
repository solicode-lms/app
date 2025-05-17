<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Model;

class FormField extends Component
{
    public Model $entity;
    public string $field;
    public ?string $partial;

    /**
     * @param  Model       $entity   L’instance Eloquent (ex : RealisationTache)
     * @param  string      $field    Le nom du champ (ex : "tache_id")
     * @param  string|null $partial  Un partial personnalisé (facultatif)
     */
    public function __construct(Model $entity, string $field, string $partial = null)
    {
        $this->entity  = $entity;
        $this->field   = $field;
        $this->partial = $partial;
    }

    /**
     * Construit le chemin du partial selon :
     * "<Package>::<model>.custom.forms.<field>"
     */
    protected function resolvePartial(): string
    {
        if ($this->partial) {
            return $this->partial;
        }

        $modelClass = get_class($this->entity);            // e.g. Modules\PkgGestionTaches\Models\RealisationTache
        $parts      = explode('\\', $modelClass);
        $package    = $parts[1] ?? 'PkgCore';              // e.g. "PkgGestionTaches"
        $modelName  = lcfirst(class_basename($modelClass));// e.g. "realisationTache"

        return "{$package}::{$modelName}.custom.forms.{$this->field}";
    }

    public function render()
    {
        $partial = $this->resolvePartial();

        return function (array $viewData) use ($partial) {
            $default = trim($viewData['slot']);
            $context = [
                'entity'  => $this->entity,
                'default' => $default,
            ];

            if (View::exists($partial)) {
                return view($partial, $context)->render();
            }

            return $default;
        };
    }
}
