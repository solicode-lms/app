@php
    $fieldName = $entity->name;

    switch ($entity->data_type) {
        case 'ManyToOne':
            $primaryKey = $entity->eRelationship->targetEModel->ePackage->name
                . '::' . lcfirst($entity->eRelationship->targetEModel->name) . '.plural';

            $fallbackKey = 'Core::msg.' . lcfirst($entity->eRelationship->targetEModel->name) . '.plural';
            break;

        case 'HasMany':
            $primaryKey = $entity->eRelationship->sourceEModel->ePackage->name
                . '::' . lcfirst($entity->eRelationship->sourceEModel->name) . '.plural';

            $fallbackKey = 'Core::msg.' . lcfirst($entity->eRelationship->sourceEModel->name) . '.plural';
            break;

        default:
            $primaryKey = $entity->eModel->ePackage->name
                . '::' . lcfirst($entity->eModel->name) . '.' . $fieldName;

            $fallbackKey = 'Core::msg.' . $fieldName;
            break;
    }

    $label = __($primaryKey) !== $primaryKey ? __($primaryKey) : __($fallbackKey);
@endphp

{{ ucfirst($label) }}
