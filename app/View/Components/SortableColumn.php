<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Modules\Core\Services\ViewStateService;

class SortableColumn extends Component
{
    public $field;
    public $label;
    public $sortable;
    public $modelname;
    protected $viewState;

    public function __construct($field, $label, $sortable = true, $modelname = null)
    {
        $this->field = $field;
        $this->label = $label;
        $this->sortable = $sortable;
        $this->modelname = $modelname;
 

        // Récupération automatique du ViewState (injecté dans le middleware)
        $this->viewState = app(ViewStateService::class);
    }

    protected function getSortKey(): string
    {
        return "sort.{$this->modelname}.{$this->field}";
    }

    public function isSorted(): bool
    {
        return $this->viewState->get($this->getSortKey()) !== null;
    }

    public function getSortDirection(): ?string
    {
        return $this->viewState->get($this->getSortKey());
    }

    public function getNextSortDirection(): ?string
    {
        return match ($this->getSortDirection()) {
            'asc' => 'desc',
            'desc' => null,
            default => 'asc',
        };
    }

    public function getSortUrl(): string
    {
        $query = request()->query();
    
        $key = "sort.{$this->modelname}.{$this->field}";
    
        // Supprimer la clé existante
        unset($query[$key]);
    
        // Ajouter la nouvelle direction si elle existe
        if ($newDir = $this->getNextSortDirection()) {
            $query[$key] = $newDir;
        }
    
        return request()->url() . '?' . http_build_query($query);
    }

    public function render()
    {
        return view('components.sortable-column');
    }
}
