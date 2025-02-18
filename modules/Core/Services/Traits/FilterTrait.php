<?php

namespace Modules\Core\Services\Traits;


trait FilterTrait
{
    public function getFieldsFilterable(): array
    {
        $this->initFieldsFilterable();
        return $this->fieldsFilterable;
    }

    /**
 * Génère un filtre ManyToOne avec des options formatées.
 *
 * @param string $field Le nom du champ.
 * @param string $model La classe du modèle.
 * @return array Le filtre formaté.
 */
protected function generateManyToOneFilter(string $label, string $field, string $model, string $display_field): array
{
    $modelInstance = new $model();

    return [
        'label' => $label,
        'field' => $field,
        'type' => 'ManyToOne',
        'options' => $model::all(['id', $display_field])
            ->map(fn($item) => ['id' => $item['id'], 'label' => $item[$display_field]])
            ->toArray(),
        'sortable' => "{$modelInstance->getTable()}.{$display_field}", // Champ à utiliser pour le tri
    ];
}

protected function generatePolymorphicFilter(string $label, string $field, string $model, string $display_field): array
{
    $modelInstance = new $model();

    return [
        'label' => $label,
        'field' => $field,
        'type' => 'Polymorphic',
        'options' => $model::all(['id', $display_field])
            ->map(fn($item) => ['id' => $item['id'], 'label' => $item[$display_field]])
            ->toArray(),
        'sortable' => "{$modelInstance->getTable()}.{$display_field}", // Champ à utiliser pour le tri
    ];
}

public function initFieldsFilterable(){
    // Il doit être appele aprés le choix de context par index par exemple , pour appliquer 
    // le scope de le contextKey
}

    
}