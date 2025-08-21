{{-- Affichage ManyToOne avec cas spéciaux --}}
@if($nature === 'badge' && !empty($entity->{$relationName}))
    <x-badge 
        :text="$entity->{$relationName}" 
        :background="$entity->{$relationName}->sysColor->hex ?? '#6c757d'" 
    />

@elseif($nature === 'couleur')
    <x-badge 
        :text="$entity->{$relationName}->name ?? ''" 
        :background="$entity->{$relationName}->hex ?? '#6c757d'" 
    />

@else
    {{ $entity->{$relationName} }}
@endif
