<?php
namespace App\View\Components;

use Illuminate\View\Component;
use Modules\Core\Services\ViewStateService;

class SortableColumn extends Component
{
    public $field;

    public $width;

    public $label;

    public $modelname;
    protected $viewState;

    /**
     * Constructeur.
     *
     * @param string $field - Le champ à trier
     * @param string $label - Le label à afficher dans la colonne
     */
    public function __construct($field, $modelname  , $label, $width = null)
    {
        $this->width = $width;
        $this->field = $field;
        $this->label = $label;
        $this->modelname = $modelname;
        $this->viewState = app(ViewStateService::class);
    }

    /**
     * Vérifie si la colonne est actuellement triée.
     *
     * @return bool
     */
    public function isSorted()
    {
        $sortVariables = $this->viewState->getSortVariables(modelName: $this->modelname);
        $currentSort = $sortVariables["sort"] ?? "";
        if (!$currentSort) {
            return false; // Pas de tri si "sort" est vide
        }
    
        // Divise les critères de tri en un tableau
        $sortArray = explode(',', $currentSort);
    
        // Vérifie si l'un des critères commence par le champ actuel suivi de "_"
        return collect($sortArray)->contains(function ($sort) {
            return str_starts_with(trim($sort), $this->field . '_');
        });
    }

    /**
     * Récupère la direction actuelle de tri pour cette colonne.
     *
     * @return string|null - 'asc', 'desc' ou null (pas de tri)
     */
    public function getSortDirection()
    {
        $sortVariables = $this->viewState->getSortVariables($this->modelname );
        $currentSort = $sortVariables["sort"] ?? "";
        return collect(explode(',', $currentSort))
            ->filter(fn($sort) => str_starts_with($sort, $this->field . '_'))
            ->map(fn($sort) => last(explode('_', $sort)) ?? 'asc') // Récupérer le dernier segment après "_"
            ->first();
    }


    /**
     * Génère la direction de tri suivante pour cette colonne.
     *
     * @return string|null - 'asc', 'desc' ou null
     */
    public function getNextSortDirection()
    {
        $currentDirection = $this->getSortDirection();

        return match ($currentDirection) {
            'asc' => 'desc',
            'desc' => null,
            default => 'asc',
        };
    }

    /**
     * Génère l'URL pour le tri suivant.
     *
     * @return string
     */
    public function getSortUrl()
    {
        $currentSort = request('sort', '');
        $sortArray = collect(explode(',', $currentSort))
            ->reject(fn($sort) => str_starts_with($sort, $this->field . '_')) // Supprime le tri actuel pour ce champ
            ->when($this->getNextSortDirection(), fn($collection) => $collection->push("{$this->field}_{$this->getNextSortDirection()}"))
            ->join(',');
    
        return request()->fullUrlWithQuery(['sort' => $sortArray]);
    }

    /**
     * Récupère la vue du composant.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('components.sortable-column');
    }
}
